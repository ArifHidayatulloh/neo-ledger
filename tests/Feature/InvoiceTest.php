<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Client;
use App\Models\Spk;
use App\Models\Invoice;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_invoice_and_finalize_spk()
    {
        $role = \App\Models\Role::create(['name' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $client = Client::create(['name' => 'ACME Corp']);

        $spk = Spk::create([
            'spk_ref' => 'NEO.001/SPK/III/2026',
            'client_id' => $client->id,
            'total_contract' => 9000000,
            'dp_amount' => 2700000,
            'final_bill' => 6300000,
            'status' => 'draft',
        ]);

        $response = $this->post(route('invoices.store'), [
            'spk_id' => $spk->id,
            'client_id' => $client->id,
            'bank_account' => 'Bank Mandiri a.n Arif Hidayatulloh',
        ]);

        $response->assertStatus(302);

        $invoice = Invoice::first();
        $this->assertNotNull($invoice);
        $this->assertEquals($invoice->spk_id, $spk->id);
        $this->assertEquals('draft', $invoice->status);

        // mark as paid
        $payResp = $this->post(route('invoices.markPaid', $invoice->id));
        $payResp->assertStatus(302);

        $invoice->refresh();
        $spk->refresh();

        $this->assertEquals('paid', $invoice->status);
        $this->assertTrue((bool)$spk->is_finalized);
    }
}
