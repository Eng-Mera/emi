FORMAT: 1A

# HTR APIs

# Restaurants [/test]
Auth Representation.

# AppHttpControllersAPIsApiPasswordController

# AppHttpControllersAPIsApiProfileController

# AppHttpControllersAPIsApiUsersController

# AppHttpControllersAPIsApiRateReviewController

## Show all users [GET /{?page,limit}]
Get a JSON representation of all the registered users.

+ Parameters
    + page: (string, optional) - The page of results to view.
        + Default: 1
    + limit: (string, optional) - The amount of results per page.
        + Default: 10

# AppHttpControllersAPIsApiFavoriteRestaurantsController

# AppHttpControllersAPIsApiFileController

# Restaurants [/restaurant]
Restaurant Representation.

## List all restaurants [GET /restaurant{?per_page,search,order,id,order_type,filters[price_from],filters[price_to],filters[distance][value],filters[distance][latitude],filters[distance][longitude],filters[category],filters[rating],filters[popularity],filters[type],filters[htr_stars]}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to list results by.
        + Default: id
    + order_type: (string, optional) - Type of sorting (Descending - Ascending).
        + Default: desc
    + filters[price_from]: (integer, optional) - Filter results by range of price.
        + Default: 
    + filters[price_to]: (integer, optional) - Filter results by range of price.
        + Default: 
    + filters[distance][value]: (integer, optional) - Filter results by distance.
        + Default: 
    + filters[distance][latitude]: (integer, optional) - Filter results by latitude.
        + Default: 
    + filters[distance][longitude]: (integer, optional) - Filter results by longitude.
        + Default: 
    + filters[category]: (integer, optional) - Filter results by category.
        + Default: 
    + filters[rating]: (integer, optional) - Filter results by rate value.
        + Default: 
    + filters[popularity]: (integer, optional) - Filter results by popularity.
        + Default: 
    + filters[type]: (integer, optional) - Filter results by type.
        + Default: restaurant
    + filters[htr_stars]: (integer, optional) - Filter results by type.
        + Default: 

# AppHttpControllersAPIsApiMenuItemController

# AppHttpControllersAPIsApiGalleryController

# AppHttpControllersAPIsApiPhotoVoteController

# AppHttpControllersAPIsApiPhotoCommentController

# AppHttpControllersAPIsApiReservationController

# AppHttpControllersAPIsApiPaymentController

# AppHttpControllersAPIsApiDeviceController

# AppHttpControllersAPIsApiOpeningDayController

# AppHttpControllersAPIsApiReviewReplyController