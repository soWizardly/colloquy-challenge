<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Meeting') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

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
            </div>
        </div>
    </div>
</x-app-layout>
