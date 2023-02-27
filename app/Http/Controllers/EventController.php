<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    public function index(){
        $search = request("search");

        if($search) {
            $event = Event::where([
                ["title", "like", "%".$search."%"]
            ])->get();
            
        } else {
            $event = Event::all();
        }


        return view("welcome", ["event" => $event, "search" => $search]);
    }

    public function create(){
        return view("events.create");
    }

    public function store(Request $request){
        $event = new Event;
        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;

        // image upload
        if($request->hasFile("image") && $request->file("image")->isValid()){
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path("img/events"), $imageName);

            $event->image = $imageName;
        }
        $user = auth()->user();
        $event->user_id = $user->id;

        $event->save();
        return redirect("/")->with("msg", "Evento criado com sucesso!");
    }

    public function show($id){
        $event =  Event::findOrFail($id);

        $user = auth()->user();
        $hasUserJoined = false;

        if($user){
            $userEvents = $user->eventsAsParticipant->toArray();

            foreach($userEvents as $userEvents){
                if($userEvents["id"] == $id) {
                    $hasUserJoined = true;
                }
            }
        }

        $eventOwner = User::where("id", $event->user_id)->first()->toArray();

        return view("events.show", ["event" => $event, "eventOwner" => $eventOwner, "hasUserJoined" => $hasUserJoined]);
    }

    public function dashboard(){
        $user = auth()->user();
        $event = $user->events;
        $eventsAsParticipant = $user->eventsAsPartcipant;

        return view("events.dashboard", ["event" => $event, "eventsAsParticipant" => $eventsAsParticipant]);
    }

    public function destroy($id){
        $event =  Event::findOrFail($id);
        if($event->image){
            unlink(public_path('img/events/' . $event->image));
        }
        
        Event::findOrFail($id)->delete();
        return redirect("/dashboard")->with("msg", "Evento excluido com sucesso!");
    }

    public function edit($id){
        $user = auth()->user();

        $event = Event::findOrFail($id);
        if($user->id != $event->user_id){
            return view("/dashboard");
        }
        return view("events.edit", ["event" => $event]);

    }

    public function update(Request $request){
        $event = Event::findOrFail($request->id);
        $data = $request->all();

        if($request->hasFile("image") && $request->file("image")->isValid()){
            unlink(public_path('img/events/' . $event->image));
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path("img/events"), $imageName);

            $data["image"] = $imageName;
        }

        Event::findOrFail($request->id)->update($data);
        return redirect("/dashboard")->with("msg", "Evento editado com sucesso!");
    }

    public function joinEvent($id){
        $user = auth()->user();
        $user->eventsAsParticipant()->attach($id);
        $event = Event::findOrFail($id);
        return redirect('/dashboard')->with("msg", "Sua presença foi confirmada!");
    }

    public function leaveEvent($id){
        $user = auth()->user();
        $user->eventsAsParticipant()->detach($id);
        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with("msg", "Sua presença foi retirada!");
    }
}