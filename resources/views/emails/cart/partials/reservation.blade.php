<!-- Reservation Details -->
<p>
    {{trans('Reservation Details')}}:
</p>

<p>
    {{trans('Date')}}: {{$reservation->date}}
</p>

<p>
    {{trans('time')}}: {{$reservation->time}}
</p>

<p>
    {{trans('number of people')}}: {{$reservation->number_of_people}}
</p>

<p>
    {{trans('amount')}}: {{$reservation->amount}}
</p>

<p>
    {{trans('discount')}}: {{$reservation->discount}}
</p>

<p>
    {{trans('total')}}: {{$reservation->total}}
</p>
