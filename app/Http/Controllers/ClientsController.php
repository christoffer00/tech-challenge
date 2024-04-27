<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientsController extends Controller
{
    public function index()
    {
        $clients = Client::where('user_id', '=', Auth::user()->id);

        foreach ($clients as $client) {
            $client->append('bookings_count');
        }

        return view('clients.index', ['clients' => $clients->get()]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function show($client)
    {
        $client = Client::with('bookings')->where('id', $client)->firstOrFail();
        if ($client->user_id !== Auth::user()->id) {
            throw new AuthorizationException();
        }

        return view('clients.show', ['client' => $client]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['string', 'max:190', 'required'],
            'email' => ['nullable', 'email', 'required_without:phone'],
            'phone' => ['nullable', 'regex:/^[\d +]*$/', 'required_without:email'],
        ]);

        $client = new Client;
        $client->name = $request->get('name');
        $client->email = $request->get('email');
        $client->phone = $request->get('phone');
        $client->address = $request->get('address');
        $client->city = $request->get('city');
        $client->postcode = $request->get('postcode');
        $client->user_id = Auth::user()->id;
        $client->save();

        return $client;
    }

    public function destroy($client)
    {
        $client = Client::where('id', $client)->firstOrFail();
        if ($client->user_id !== Auth::user()->id) {
            throw new AuthorizationException();
        }

        Client::where('id', $client->id)->delete();

        return 'Deleted';
    }
}
