<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create or Edit Meeting') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <p>All fields are required!</p>
                    <br><br>

                    <form method="POST" action="{{ $route }}">
                        @csrf

                        @if(!empty($meeting))
                            @method("PUT")
                        @endif

                        <label>
                            Name:<br>
                            <input type="text" name="name" value="{{ $meeting->name??null }}">
                        </label>
                        <br><br>
                        @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label>
                            Type:<br>
                            <select name="meeting_type_id">
                                @foreach($types as $type)
                                    <option value="{{$type->id}}"
                                            @if(!empty($meeting) && $meeting->meeting_type_id == $type->id) selected @endif>{{$type->name}}</option>
                                @endforeach
                            </select>
                        </label>
                        <br><br>
                        @error('meeting_type_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label>
                            Case:<br>
                            <select name="legal_case_id">
                                @foreach($cases as $case)
                                    <option value="{{$case->id}}"
                                            @if(!empty($meeting) && $meeting->legal_case_id == $case->id) selected @endif>{{$case->name}}</option>
                                @endforeach
                            </select>
                        </label>
                        <br><br>
                        @error('legal_case_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label>
                            Description<br>
                            <textarea name="description">{{ $meeting->description??null }}</textarea>
                        </label>
                        <br><br>
                        @error('description')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label>
                            Start At:<br>
                            <input type="datetime-local" name="start_at" value="{{ $meeting->start_at??null }}">
                        </label>
                        <br><br>
                        @error('start_at')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label>
                            Participants:<br>
                            <select name="participants[]" multiple>
                                @foreach($participants as $participant)
                                    <option value="{{$participant->id}}"
                                            @if(!empty($selected_participant_ids) &&
                                            in_array($participant->id, $selected_participant_ids))
                                                selected
                                        @endif>
                                        {{$participant->last_name}}, {{$participant->first_name}}
                                    </option>
                                @endforeach
                            </select>
                        </label>
                        <br><br>
                        @error('participants')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <input class="bg-indigo-50 border border-b" type="submit" value="submit">

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
