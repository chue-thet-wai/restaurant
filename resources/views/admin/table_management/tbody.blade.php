@if($branchStatus != '') 
    <tr>
        <td><p class="p-4">{{$branchStatus}}</p></td>
    </tr>
@else
    @foreach ($tables as $table)
    <tr>
        <th class="table-label" rowspan="2">
            <button class="table-btn">{{ $table->table_name }}</button>
        </th>
        <td colspan="{{ count($timeslots) }}">
            <div class="timeslot-container">
                <table>
                    <tr>
                        @foreach ($timeslots as $timeslot)
                            <td>
                                <button class="time-btn">{{ \Carbon\Carbon::parse($timeslot->time)->format('g:i A') }}</button>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($timeslots as $timeslot)
                            <td>
                                @php
                                    $availabilityKey = "{$table->id}_{$timeslot->id}";
                                    $isChecked = $tableAvailability->has($availabilityKey) && ($tableAvailability[$availabilityKey]->is_available==false);
                                    $currentDateTime = now()->addHours(6)->addMinutes(30);
                                    $timeslotDateTime = \Carbon\Carbon::parse($timeslot->time)->setDate($date->year, $date->month, $date->day); 
                                    $isPastTime = $currentDateTime->greaterThan($timeslotDateTime); 
                                @endphp
                                <input type="checkbox" 
                                       class="table-switch" 
                                       data-table-id="{{ $table->id }}" 
                                       data-timeslot-id="{{ $timeslot->id }}" 
                                       data-date="{{ $date->format('Y-m-d') }}" 
                                       {{ $isChecked ? 'checked' : '' }}
                                       {{ $isPastTime ? 'disabled' : '' }} 
                                       data-status="{{ $isPastTime ? 'gray' : '' }}" />
                            </td>
                        @endforeach
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr class="table-row-spacing"></tr>
    @endforeach
@endif
