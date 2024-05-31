<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TicketRequest;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        //http://ticketycode.test/admin/events/10/tickets
        $tickets = Ticket::where('event_id', $event->id)->paginate(10);
        return view('admin.event.ticket.index', [
            'tickets' => $tickets,
            'event' => $event
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {

        return view('admin.event.ticket.form', [
            'event' => $event
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Event $event, TicketRequest $request)
    {
        //Add event id
        $request->merge([
            'event_id' => $event->id,
        ]);

        //create ticket
        Ticket::create($request->all());

        //return to index
        return redirect()->route('admin.events.tickets.index', $event->id)->with('success', 'Tiket Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event, Ticket $ticket)
    {


        return view('admin.event.ticket.form', [
            'ticket' => $ticket,
            'event' => $event
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Event $event, TicketRequest $request, string $id)
    {


        //update ticket
        Ticket::find($id)->update($request->all());

        //return to index
        return redirect()->route('admin.events.tickets.index', $event->id)->with('success', 'Tiket Berhasil Di ubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.events.tickets.index', $event->id)->with('success', 'Tiket Berhasil dihapus');
    }
}
