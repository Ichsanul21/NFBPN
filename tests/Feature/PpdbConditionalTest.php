<?php

namespace Tests\Feature;

use App\Models\PpdbFormField;
use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PpdbConditionalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'super-admin']);
        Role::firstOrCreate(['name' => 'orang-tua']);

        $this->period = PpdbPeriod::create([
            'name' => 'Gelombang Uji',
            'jenjang' => 'sdit',
            'starts_on' => now()->subDay()->format('Y-m-d'),
            'ends_on' => now()->addMonth()->format('Y-m-d'),
            'is_active' => true,
        ]);

        PpdbFormField::create([
            'jenjang' => 'sdit', 'key' => 'transportasi', 'label' => 'Transportasi',
            'type' => 'select', 'options' => ['Antar jemput', 'Lainnya'],
            'is_required' => true, 'sort_order' => 1,
        ]);
        PpdbFormField::create([
            'jenjang' => 'sdit', 'key' => 'transport_lain', 'label' => 'Sebutkan transportasi',
            'type' => 'text', 'is_required' => true, 'sort_order' => 2,
            'visible_if_field' => 'transportasi', 'visible_if_operator' => 'equals',
            'visible_if_value' => 'Lainnya',
        ]);

        $this->ortu = User::factory()->create();
        $this->ortu->assignRole('orang-tua');
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super-admin');
    }

    protected function basePayload(array $answers): array
    {
        return [
            'period_id' => $this->period->id,
            'child_name' => 'Anak Uji',
            'child_birthdate' => '2019-01-01',
            'parent_name' => 'Ortu Uji',
            'whatsapp' => '081234567890',
            'answers' => $answers,
        ];
    }

    public function test_hidden_conditional_field_is_skipped_and_stripped(): void
    {
        $res = $this->actingAs($this->ortu)->post('/ppdb', $this->basePayload([
            'transportasi' => 'Antar jemput',
            'transport_lain' => 'Seharusnya dibuang',
        ]));

        $res->assertRedirect();
        $reg = PpdbRegistration::first();
        $this->assertNotNull($reg);
        $this->assertSame('Antar jemput', $reg->answers['transportasi']);
        $this->assertArrayNotHasKey('transport_lain', $reg->answers ?? []);
    }

    public function test_visible_conditional_required_field_is_enforced(): void
    {
        $res = $this->actingAs($this->ortu)->post('/ppdb', $this->basePayload([
            'transportasi' => 'Lainnya',
        ]));

        $res->assertSessionHasErrors('answers.transport_lain');
        $this->assertSame(0, PpdbRegistration::count());
    }

    public function test_visible_conditional_field_accepts_value(): void
    {
        $res = $this->actingAs($this->ortu)->post('/ppdb', $this->basePayload([
            'transportasi' => 'Lainnya',
            'transport_lain' => 'Ojek',
        ]));

        $res->assertRedirect();
        $this->assertSame('Ojek', PpdbRegistration::first()->answers['transport_lain']);
    }

    public function test_admin_cannot_create_condition_cycle(): void
    {
        $a = PpdbFormField::where('key', 'transportasi')->first();

        $res = $this->actingAs($this->admin)->put("/admin/fields/{$a->id}", [
            'jenjang' => 'sdit',
            'label' => 'Transportasi',
            'type' => 'select',
            'options' => ['Antar jemput', 'Lainnya'],
            'visible_if_field' => 'transport_lain',
            'visible_if_operator' => 'equals',
            'visible_if_value' => 'x',
        ]);

        $res->assertStatus(422);
        $this->assertNull($a->fresh()->visible_if_field);
    }

    public function test_quick_create_returns_to_studio_with_inspector(): void
    {
        $res = $this->actingAs($this->admin)->post('/admin/fields/quick', [
            'jenjang' => 'sdit',
            'type' => 'text',
        ]);

        $field = PpdbFormField::where('label', 'Pertanyaan baru')->first();
        $this->assertNotNull($field);
        $res->assertRedirect(route('admin.fields.index', ['jenjang' => 'sdit', 'edit' => $field->id]));
    }

    public function test_reorder_persists_sequence(): void
    {
        $ids = PpdbFormField::forJenjang('sdit')->ordered()->pluck('id')->all();
        $reversed = array_reverse($ids);

        $res = $this->actingAs($this->admin)->postJson('/admin/fields/reorder', [
            'jenjang' => 'sdit',
            'order' => $reversed,
        ]);

        $res->assertOk()->assertJson(['ok' => true]);
        $this->assertSame($reversed, PpdbFormField::forJenjang('sdit')->ordered()->pluck('id')->all());
    }

    public function test_cannot_delete_trigger_field(): void
    {
        $trigger = PpdbFormField::where('key', 'transportasi')->first();

        $res = $this->actingAs($this->admin)->delete("/admin/fields/{$trigger->id}");

        $res->assertRedirect();
        $res->assertSessionHas('error');
        $this->assertNotNull(PpdbFormField::find($trigger->id));
    }

    public function test_options_array_saved_from_rows(): void
    {
        $field = PpdbFormField::where('key', 'transportasi')->first();

        $res = $this->actingAs($this->admin)->put("/admin/fields/{$field->id}", [
            'jenjang' => 'sdit',
            'label' => 'Transportasi',
            'type' => 'select',
            'options' => ['Antar jemput', 'Lainnya', 'Sepeda'],
            'is_required' => '1',
        ]);

        $res->assertRedirect();
        $this->assertSame(
            ['Antar jemput', 'Lainnya', 'Sepeda'],
            $field->fresh()->options
        );
    }

    public function test_inactive_field_hidden_from_public_form(): void
    {
        $field = PpdbFormField::where('key', 'transportasi')->first();
        $field->update(['is_active' => false]);

        $res = $this->actingAs($this->ortu)->get('/ppdb');

        $res->assertOk();
        $res->assertDontSee('name="answers[transportasi]"', false);
    }
}
