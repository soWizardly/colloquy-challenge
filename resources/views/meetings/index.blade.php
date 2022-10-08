<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upcoming Meetings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('meetings.index') }}">
                        <p>Filter By: </p>
                        @if(!empty($cases))
                            <label for="case">Case:</label>
                            <select name="case">
                                <option value="all" @if(request("case") == "all") selected @endif>All</option>
                                @foreach($cases as $case)
                                    <option value="{{$case->id}}"
                                            @if(request("case") == $case->id) selected @endif>{{$case->name}}</option>
                                @endforeach
                            </select>
                            <br>
                        @endif

                        @if(!empty($types))
                            <label for="type">Type:</label>
                            <select name="type">
                                <option value="all" @if(request("type") == "all") selected @endif>All</option>
                                @foreach($types as $type)
                                    <option value="{{$type->id}}"
                                            @if(request("type") == $type->id) selected @endif>{{$type->name}}</option>
                                @endforeach
                            </select>
                            <br>
                        @endif

                        <br>
                        <label for="sort">Sort By:</label>
                        <select name="sort">
                            <option value="time" @if(request("sort") != "participants") selected @endif>Soonest First
                            </option>
                            <option value="participants" @if(request("sort") == "participants") selected @endif>Most
                                Participants
                            </option>
                        </select>
                        <br>

                        <button class="bg-indigo-50 border border-b" type="submit">Apply</button>
                        <br><br>
                    </form>

                    <a class="bg-indigo-50 border border-b" href="{{route("meetings.create")}}">Create New Meeting</a>
                    <hr class="mt-2 mb-4">

                    @if(!empty($meetings))
                        <p>Showing {{ count($meetings) }} Meeting(s)</p>
                        <br>
                        @foreach($meetings as $meeting)
                            <div>
                                <a href="{{ route("meetings.show", $meeting->id) }}"
                                   class="bg-indigo-50 border border-b">View</a>
                                <br>
                                <a href="{{ route("meetings.edit", $meeting->id) }}"
                                   class="bg-indigo-50 border border-b">Edit</a>
                                <br>
                                <form method="POST" action="{{ route("meetings.destroy", $meeting->id) }}">
                                    @csrf
                                    @method("DELETE")
                                    <input class="bg-indigo-50 border border-b" type="submit" value="DELETE">
                                </form>
                                <p>
                                    <strong>Meeting Name:</strong> {{$meeting->name}} <br>
                                    <strong>Meeting Description:</strong> {{$meeting->description}} <br>
                                    <strong>Meeting Starts:</strong> {{$meeting->start_at}} <br>
                                    <strong>Legal Case:</strong> {{$meeting->legalCase->name}} <br>
                                    <strong>Legal Case Description:</strong> {{$meeting->legalCase->description}} <br>
                                    <strong>Meeting Type:</strong> {{$meeting->meetingType->name}} <br>
                                    <strong>Meeting Participants:</strong> ({{ $meeting->participants_count }})
                                    @if(!empty($meeting->participants))
                                        <br>
                                        @foreach($meeting->participants as $participant)
                                            {{$participant->last_name}}, {{$participant->first_name}} <br>
                                        @endforeach
                                    @endif
                                </p>
                            </div>
                            <hr class="mt-2 mb-4">
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
