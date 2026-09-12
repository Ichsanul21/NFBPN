<?php

namespace Tests\Feature;

use App\Models\PpdbCommitment;
use App\Models\PpdbDocument;
use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PpdbDocumentsCommitmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        Role::firstOrCreate(['name' => 'super-admin']);
        Role::firstOrCreate(['name' => 'orang-tua']);

        $this->period = PpdbPeriod::create([
            'name' => 'Gelombang Uji', 'jenjang' => 'sdit',
            'starts_on' => now()->subDay()->format('Y-m-d'),
            'ends_on' => now()->addMonth()->format('Y-m-d'),
            'is_active' => true,
        ]);
        $this->doc = PpdbDocument::create([
            'jenjang' => 'sdit', 'label' => 'Kartu Keluarga', 'wajib' => true,
            'urut' => 0, 'allowed' => ['pdf'], 'max_kb' => 2048, 'compress' => false,
        ]);
        PpdbCommitment::create(['jenjang' => 'sdit', 'teks' => 'Saya setuju.']);

        $this->ortu = User::factory()->create();
        $this->ortu->assignRole('orang-tua');
        $this->reg = PpdbRegistration::create([
            'registration_no' => 'NF-2026-000001',
            'period_id' => $this->period->id,
            'user_id' => $this->ortu->id,
            'jenjang' => 'sdit',
            'child_name' => 'Anak Uji',
            'child_birthdate' => '2019-01-01',
            'parent_name' => 'Ortu Uji',
            'whatsapp' => '081234567890',
            'status' => 'terkirim',
        ]);
    }

    protected function guestPayload(array $extra = []): array
    {
        return array_merge([
            'parent_name' => 'Ortu Baru',
            'email' => 'baru-unik@example.com',
            'whatsapp' => '081222333444',
            'password' => 'Rahasia123!',
            'password_confirmation' => 'Rahasia123!',
            'period_id' => $this->period->id,
            'child_name' => 'Anak Baru',
            'child_birthdate' => '2019-06-06',
        ], $extra);
    }

    public function test_commitment_required_when_text_exists(): void
    {
        $res = $this->post('/ppdb', $this->guestPayload());

        $res->assertSessionHasErrors('komitmen');
        $this->assertSame(1, PpdbRegistration::count());
    }

    public function test_commitment_snapshot_stored_on_submit(): void
    {
        $res = $this->post('/ppdb', $this->guestPayload(['komitmen' => '1']));

        $reg = PpdbRegistration::where('child_name', 'Anak Baru')->first();
        $this->assertNotNull($reg);
        $this->assertSame('Saya setuju.', $reg->komitmen_teks);
        $this->assertNotNull($reg->komitmen_at);
        $res->assertRedirect(route('portal.show', $reg));
    }

    public function test_document_upload_follows_per_doc_rules(): void
    {
        $pdf = UploadedFile::fake()->create('kk.pdf', 500, 'application/pdf');

        $res = $this->actingAs($this->ortu)->post(
            route('portal.berkas', $this->reg),
            ['doc_id' => $this->doc->id, 'berkas' => $pdf]
        );

        $res->assertSessionHasNoErrors();
        $stored = $this->reg->fresh()->answers['dokumen'][$this->doc->docKey()] ?? null;
        $this->assertNotNull($stored);
        Storage::disk('public')->assertExists($stored['path']);
    }

    public function test_document_rejects_wrong_type_and_size(): void
    {
        $exe = UploadedFile::fake()->create('jahat.exe', 100, 'application/x-msdownload');
        $res = $this->actingAs($this->ortu)->post(
            route('portal.berkas', $this->reg),
            ['doc_id' => $this->doc->id, 'berkas' => $exe]
        );
        $res->assertSessionHasErrors('berkas');

        $big = UploadedFile::fake()->create('besar.pdf', 3000, 'application/pdf');
        $res2 = $this->actingAs($this->ortu)->post(
            route('portal.berkas', $this->reg),
            ['doc_id' => $this->doc->id, 'berkas' => $big]
        );
        $res2->assertSessionHasErrors('berkas');
    }

    public function test_assisted_registration_by_tu(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        $res = $this->actingAs($admin)->post(route('admin.registrations.manual.store'), [
            'period_id' => $this->period->id,
            'ortu_mode' => 'baru',
            'new_name' => 'Ortu Bantuan',
            'new_email' => 'bantuan@example.com',
            'new_whatsapp' => '081999000111',
            'new_password' => 'Temp123!x',
            'child_name' => 'Anak Bantuan',
            'child_birthdate' => '2018-02-02',
            'parent_name' => 'Ortu Bantuan',
            'whatsapp' => '081999000111',
            'komitmen' => '1',
        ]);

        $reg = PpdbRegistration::where('child_name', 'Anak Bantuan')->first();
        $this->assertNotNull($reg);
        $this->assertTrue($reg->dibantu_tu);
        $this->assertSame($admin->id, $reg->assisted_by);
        $this->assertSame('Saya setuju.', $reg->komitmen_teks);
        $this->assertTrue($reg->histories()->where('to_status', 'terkirim')->exists());
        $res->assertRedirect(route('admin.registrations.show', $reg));
    }

    public function test_portal_checklist_and_logout_visible(): void
    {
        $res = $this->actingAs($this->ortu)->get(route('portal.index'));

        $res->assertOk();
        $res->assertSee('Keluar akun', false);
        $detail = $this->actingAs($this->ortu)->get(route('portal.show', $this->reg));
        $detail->assertOk();
        $detail->assertSee('Dokumen wajib', false);
    }
}
