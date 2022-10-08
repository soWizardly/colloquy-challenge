<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Models\LegalCase;
use App\Models\Meeting;
use App\Models\MeetingType;
use App\Models\Participant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MeetingController extends Controller
{

    /**
     * Show the list of Meetings
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $request = $request->validate([
            'case' => 'sometimes|exclude_if:case,all|exists:legal_cases,id',
            'type' => 'sometimes|exclude_if:type,all|exists:meeting_types,id',
            'sort' => ['sometimes', Rule::in(["time", "participants"])]
        ]);

        $meetings = Meeting::with("legalCase", "meetingType", "participants")
            ->withCount("participants");

        if (!empty($request["case"]) && $request["case"] != "all") {
            $meetings = $meetings->where("legal_case_id", "=", $request["case"]);
        }

        if (!empty($request["type"]) && $request["type"] != "all") {
            $meetings = $meetings->where("meeting_type_id", "=", $request["type"]);
        }

        if (!empty($request["sort"]) && $request["sort"] == "participants") {
            $meetings = $meetings->orderBy("participants_count", "desc");
        } else {
            $meetings = $meetings->orderBy("start_at", "asc");
        }

        return view('meetings.index', [
            'meetings' => $meetings->orderBy("start_at", $request["time"] ?? "asc")
                ->orderBy("participants_count", $request["participants"] ?? "desc")
                ->get(),
            "types" => MeetingType::all(),
            "cases" => LegalCase::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('meetings.createedit', [
            "route" => route("meetings.store"),
            "types" => MeetingType::all(),
            "cases" => LegalCase::all(),
            "participants" => Participant::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMeetingRequest $meeting
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function store(StoreMeetingRequest $meeting)
    {
        $data = $meeting->validated();

        $meeting = new Meeting;
        $meeting->name = $data["name"];
        $meeting->description = $data["description"];
        $meeting->start_at = $data["start_at"];
        $meeting->legalCase()->associate(LegalCase::findOrFail($data["legal_case_id"]));
        $meeting->meetingType()->associate(MeetingType::findOrFail($data["meeting_type_id"]));
        $meeting->saveOrFail();

        $meeting->refresh()->participants()->saveMany(array_map(
            fn($id) => Participant::findOrFail($id),
            (array)$data["participants"]
        ));

        return redirect()->route("meetings.index");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        return view('meetings.show', [
            "meeting" => Meeting::withCount("participants")->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $meeting = Meeting::findOrFail($id);
        return view('meetings.createedit', [
            "meeting" => $meeting,
            "route" => route("meetings.update", $meeting->id),
            "types" => MeetingType::all(),
            "cases" => LegalCase::all(),
            "participants" => Participant::all(),
            "selected_participant_ids" => $meeting->participants()->get()->map(function ($participant) {
                return $participant->id;
            })->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMeetingRequest $meeting
     * @param int $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(UpdateMeetingRequest $meeting, $id)
    {
        $data = $meeting->validated();

        /** @var Meeting $meeting */
        $meeting = Meeting::findOrFail($id);
        $meeting->name = $data["name"];
        $meeting->description = $data["description"];
        $meeting->start_at = $data["start_at"];
        $meeting->legalCase()->associate(LegalCase::findOrFail($data["legal_case_id"]));
        $meeting->meetingType()->associate(MeetingType::findOrFail($data["meeting_type_id"]));
        $meeting->saveOrFail();

        // Clear old assoc.
        $meeting->participants()->detach();

        $meeting->participants()->saveMany(array_map(
            fn($id) => Participant::findOrFail($id),
            (array)$data["participants"]
        ));

        return redirect()->route("meetings.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        Meeting::findOrFail($id)->delete();
        return redirect()->back();
    }
}
