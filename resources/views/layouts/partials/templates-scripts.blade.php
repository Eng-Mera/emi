<srcipt class="hide" id="user-action-template" type="text/x-jsrender">
    <a title="View" href=" {{ url('admin/user/@%:username%@') }}"><span class="glyphicon glyphicon-eye-open"></span></a>
    <a title="Edit" href=" {{ url('admin/user/@%:username%@/edit') }}"><span
                class="glyphicon glyphicon-edit"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form class="delete-action" action="{{ url('admin/user/@%:username%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="role-action-template" type="text/x-jsrender">
    <a title="View" href=" {{ url('admin/role/@%:name%@') }}"><span class="glyphicon glyphicon-eye-open"></span></a>
    <a title="Edit" href=" {{ url('admin/role/@%:name%@/edit') }}"><span class="glyphicon glyphicon-edit"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form class="delete-action" action="{{ url('admin/role/@%:name%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="permission-action-template" type="text/x-jsrender">
    <a title="View" href=" {{ url('admin/permission/@%:name%@') }}"><span
                class="glyphicon glyphicon-eye-open"></span></a>
    <a title="Edit" href=" {{ url('admin/permission/@%:name%@/edit') }}"><span class="glyphicon glyphicon-edit"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form class="delete-action" action="{{ url('admin/permission/@%:name%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>


<srcipt class="hide" id="restaurant-action-template" type="text/x-jsrender">
    <a title="View" href=" {{ url('admin/restaurant/@%:slug%@') }}"><span
                class="glyphicon glyphicon-eye-open"></span></a>
    <a title="Edit" href=" {{ url('admin/restaurant/@%:slug%@/edit') }}"><span class="glyphicon glyphicon-edit"></span></a>
    <a title="Menu Items" href=" {{ url('admin/restaurant/@%:slug%@/menu-item') }}"><span
                class="glyphicon glyphicon-list"></span></a>
    <a title="Branches" href=" {{ url('admin/restaurant/@%:slug%@/branch') }}"><span
                class="glyphicon glyphicon-list"></span></a>

    <a title="Gallery" href=" {{ url('admin/restaurant/@%:slug%@/gallery') }}"><span
                class="glyphicon glyphicon-picture"></span></a>
    <a title="Facilities" href=" {{ url('admin/restaurant/@%:slug%@/facility') }}"><span
                class="glyphicon glyphicon-certificate"></span></a>
    <a title="Opening days" href=" {{ url('admin/restaurant/@%:slug%@/opening-days') }}"><span
                class="glyphicon glyphicon-calendar"></span></a>

    <a title="Remove" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>

    <form action="{{ url('admin/restaurant/@%:slug%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="report-action-template" type="text/x-jsrender">
    <a title="View" href=" {{ url('admin/report/@%:id%@') }}"><span class="glyphicon glyphicon-eye-open"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>

    <form action="{{ url('admin/report/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>


<srcipt class="hide" id="claim-action-template" type="text/x-jsrender">
    <a title="View" href=" {{ url('admin/claim/@%:id%@') }}"><span class="glyphicon glyphicon-eye-open"></span></a>
    <a title="Edit" href=" {{ url('admin/claim/@%:id%@/edit') }}"><span class="glyphicon glyphicon-ok"></span></a>
    <a title="Cancel" href=" {{ url('admin/claim/@%:id%@/cancel') }}"><span
                class="glyphicon glyphicon-remove"></span></a>
</srcipt>

<srcipt class="hide" id="reservation-action-template" type="text/x-jsrender">
    <a title="View" href=" {{ url('admin/reservation/@%:id%@') }}"><span class="glyphicon glyphicon-eye-open"></span></a>
    <a title="Request Review" href=" {{ url('admin/reservation/request-review/@%:restaurant%@/@%:user%@') }}"><span class="glyphicon glyphicon-question-sign"></span></a>
    <a title="Change" href=" {{ url('admin/reservation/edit/@%:id%@') }}"><span class="glyphicon glyphicon-refresh"></span></a>
    <a title="Accept" href="#"><span class="accept-reservation glyphicon glyphicon-ok"></span></a>
    <form action="{{ url('admin/reservation/@%:id%@/accept') }}" method="POST">
        <input type="hidden" name="_method" value="PATCH"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
    <a title="Arrived" href="#"><span class="arrived-reservation glyphicon glyphicon-map-marker"></span></a>
    <form action="{{ url('admin/reservation/@%:id%@/arrived') }}" method="POST">
        <input type="hidden" name="_method" value="PATCH"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
    <a title="Change" href=" {{ url('admin/coupon/create/@%:user%@') }}"><span class="glyphicon glyphicon-gift"></span></a>


    <a title="Reject" href="#"><span class="reject-reservation glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/reservation/@%:id%@/reject') }}" method="POST">
        <input type="hidden" name="_method" value="PATCH"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="restaurant-grid-details" type="text/x-jsrender">
    <div class="caption">
        <a href="{{ url('/admin/restaurant/@%:slug%@/edit') }}">
            <h4>@%:name%@</h4>
        </a>
        <span class="glyphicon glyphicon-home"></span> @%:address%@<br/>
        <span class="glyphicon glyphicon-phone"></span> @%:phone%@<br/>
        <span class="glyphicon  glyphicon-comment"></span> @%:email%@<br/>

    </div>
</srcipt>

<srcipt class="hide" id="menuitem-action-template" type="text/x-jsrender">

    <a title="Edit" href=" {{ url('admin/restaurant/@%:restaurant_slug%@/menu-item/@%:slug%@/edit') }}"><span
                class="glyphicon glyphicon-magnet"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/restaurant/@%:restaurant_slug%@/menu-item/@%:slug%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="reservation-policy-action-template" type="text/x-jsrender">

    <a title="Edit" href=" {{ url('admin/restaurant/@%:restaurant_slug%@/reservation-policy/@%:id%@/edit') }}"><span
                class="glyphicon glyphicon-magnet"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/restaurant/@%:restaurant_slug%@/reservation-policy/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="branch-action-template" type="text/x-jsrender">
    <a title="View" href=" {{ url('admin/restaurant/@%:restaurant_slug%@/branch/@%:slug%@') }}"><span
                class="glyphicon glyphicon-eye-open"></span></a>
    <a title="Edit" href=" {{ url('admin/restaurant/@%:restaurant_slug%@/branch/@%:slug%@/edit') }}"><span
                class="glyphicon glyphicon-magnet"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/restaurant/@%:restaurant_slug%@/branch/@%:slug%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="opening-days-action-template" type="text/x-jsrender">

    <a title="Edit" href=" {{ url('admin/restaurant/@%:restaurant_slug%@/opening-days/@%:id%@/edit') }}"><span
                class="glyphicon glyphicon-edit"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/restaurant/@%:restaurant_slug%@/opening-days/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="rate-review-action-template" type="text/x-jsrender">

    <a title="Reply" href=" {{ url('admin/restaurant/@%:restaurant_slug%@/rates/@%:id%@ ') }}"><span
                class="glyphicon glyphicon-eye-open"></span></a>

    <a title="Reply" href=" {{ url('admin/reply-review/@%:restaurant_slug%@/@%:id%@/create ') }}"><span
                class="glyphicon glyphicon-send"></span></a>

    {{--<a title="Edit" href=" {{ url('admin/restaurant/@%:restaurant_slug%@/rates/@%:id%@/edit') }}"><span--}}
                {{--class="glyphicon glyphicon-edit"></span></a>--}}


    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/restaurant/@%:restaurant_slug%@/rates/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>


<srcipt class="hide" id="job-title-action-template" type="text/x-jsrender">

    <a title="Edit" href=" {{ url('admin/job-title/@%:id%@/edit') }}"><span
                class="glyphicon glyphicon-edit"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/job-title/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="job-vacancy-action-template" type="text/x-jsrender">

    <a title="Edit" href=" {{ url('admin/restaurant/@%:restaurant_slug%@/job-vacancy/@%:id%@/edit') }}"><span
                class="glyphicon glyphicon-edit"></span></a>

    <a title="View" href=" {{ url('admin/restaurant/@%:restaurant_slug%@/applied-users/@%:id%@') }}"><span
                class="glyphicon glyphicon-bullhorn"></span></a>

    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/restaurant/@%:restaurant_slug%@/job-vacancy/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="category-action-template" type="text/x-jsrender">

    <a title="Edit" href=" {{ url('admin/category/@%:id%@/edit') }}"><span
                class="glyphicon glyphicon-edit"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/category/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="movie-action-template" type="text/x-jsrender">

    <a title="Edit" href=" {{ url('admin/movie/@%:id%@/edit') }}"><span
                class="glyphicon glyphicon-edit"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/movie/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="admin-review-action-template" type="text/x-jsrender">

    <a title="Edit" href=" {{ url('admin/admin-review/@%:id%@/edit') }}"><span
                class="glyphicon glyphicon-edit"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/admin-review/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="facility-action-template" type="text/x-jsrender">

    <a title="Edit" href=" {{ url('admin/facility/@%:id%@/edit') }}"><span
                class="glyphicon glyphicon-edit"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/facility/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>

<srcipt class="hide" id="city-action-template" type="text/x-jsrender">

    <a title="Edit" href=" {{ url('admin/city/@%:id%@/edit') }}"><span
                class="glyphicon glyphicon-edit"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/city/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>


<srcipt class="hide" id="dishcategory-action-template" type="text/x-jsrender">

    <a title="Edit" href=" {{ url('admin/dish-category/@%:id%@/edit') }}"><span
                class="glyphicon glyphicon-edit"></span></a>
    <a title="Delete" href="#"><span class="delete-action glyphicon glyphicon-remove"></span></a>
    <form action="{{ url('admin/dish-category/@%:id%@') }}" method="POST">
        <input type="hidden" name="_method" value="DELETE"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    </form>
</srcipt>