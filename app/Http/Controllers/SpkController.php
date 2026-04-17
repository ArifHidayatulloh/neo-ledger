<?php

namespace App\Http\Controllers;

use App\Models\Spk;
use App\Models\Client;
use Illuminate\Http\Request;

class SpkController extends Controller
{
    public function index()
    {
        $spks = Spk::with('client')->orderBy('spk_ref')->paginate(20);
        return view('spks.index', compact('spks'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('spks.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'spk_ref' => 'required|string|max:255|unique:spks,spk_ref',
            'client_id' => 'nullable|exists:clients,id',
            'total_contract' => 'nullable|numeric',
            'dp_amount' => 'nullable|numeric',
            'context' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $data['final_bill'] = ($data['total_contract'] ?? 0) - ($data['dp_amount'] ?? 0);
        Spk::create($data);
        return redirect()->route('spks.index')->with('success', 'SPK created.');
    }

    public function edit(Spk $spk)
    {
        $clients = Client::orderBy('name')->get();
        return view('spks.edit', compact('spk', 'clients'));
    }

    public function update(Request $request, Spk $spk)
    {
        $data = $request->validate([
            'spk_ref' => 'required|string|max:255|unique:spks,spk_ref,'.$spk->id,
            'client_id' => 'nullable|exists:clients,id',
            'total_contract' => 'nullable|numeric',
            'dp_amount' => 'nullable|numeric',
            'context' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $data['final_bill'] = ($data['total_contract'] ?? 0) - ($data['dp_amount'] ?? 0);
        $spk->update($data);
        return redirect()->route('spks.index')->with('success', 'SPK updated.');
    }

    public function show(Spk $spk)
    {
        return view('spks.show', compact('spk'));
    }

    public function destroy(Spk $spk)
    {
        $spk->delete();
        return redirect()->route('spks.index')->with('success', 'SPK deleted.');
    }
}
