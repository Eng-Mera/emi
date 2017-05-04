FORMAT: 1A

# HTR APIs

# Auth
Register new account.Create Access token.
Reset Account password

## Create New User [POST /api/v1/user/register]


+ Parameters
    + name: (string, required) - The name of registered user.
        + Default: 
    + username: (string, required) - the username of registered user.
        + Default: 
    + email: (string, required) - The email of registered user
        + Default: 
    + mobile: (string, required) - The mobile number of registered user
        + Default: 
    + password: (string, required) - Password of account.
        + Default: 
    + password_confirmation: (string, required) - Password Repeat.
        + Default: 
    + profile_image: (string, required) - Profile picture in base64 encode format.
        + Default: 
    + fb_id: (integer, optional) - The facebook id.
        + Default: 
    + google_id: (integer, optional) - The google account id.
        + Default: 
    + intgm_id: (integer, optional) - The instagram id.
        + Default: 
    + user_type: (string, optional) - User type job-seaker, restaurant-manager.
        + Default: 

## Get Access Token by Client Credentials [POST /oauth/access_token]


+ Parameters
    + grant_type: (string, required) - The type of grant values= password,social_media,social_media_update
    + client_id: (string, required) - The oauth client id
    + client_secret: (string, required) - The oauth client secret
    + username: (string, required) - The email or username of user
    + password: (string, required) - The Password of user
    + social_type: (string, required) - In case of grant type is social_media_update The types of social media facebook, google, instagram
    + social_id: (string, required) - In case of grant type is social_media The id of account of social media account
    + email: (string, required) - In case of grant type is social_media_update The email user account
    + refresh_token: (string, optional) - After expiration of access token we can use this key to refresh the token

## Reset account Password [POST /password/reset]


+ Parameters
    + email: (string, required) - The email of account to be reset.
    + reset_url: (string, optional) - The url which the application will send email to. http://{{your_url}}/{token}. Please note that the url must contain token word

## Complete Reset Password [POST /password/complete-reset]


+ Parameters
    + email: (string, required) - The email of account to be reset.
    + token: (string, required) - The token that sent to user through email
    + password: (string, required) - The new password
    + password_confirmation: (string, required) - New Password confirmation
     * 

# Users [/api/v1/user]
Users.

## List all users [GET /api/v1/user{?per_page,search,order,id,order_type}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by (name - id - email).
        + Default: id
    + order_type: (string, optional) - Type of sorting (Descending - Ascending).
        + Default: desc

## View User information and details [GET /api/v1/user/{username}]


+ Parameters
    + username: (string, required) - Username of specified user.

## Update the specified User [PUT /api/v1/user/{username}]


+ Parameters
    + username: (string, required) - Username of specified user.
    + name: (string, required) - User first and last name.
        + Default: 
    + email: (string, required) - Email address
        + Default: 
    + current_password: (string, optional) - Required when trying to change password
        + Default: 
    + password: (float, optional) - New Password.
        + Default: 
    + password_confirmation: (float, optional) - Confirm of new password.
        + Default: 
    + dob: (string, optional) - User Date of birth.
        + Default: 
    + mobile: (string, optional) - User mobile phone.
        + Default: 
    + address: (integer, optional) - User Address.
        + Default: 
    + qualification: (string, optional) - User Qualification.
        + Default: 
    + current_employee: (string, optional) - User current employee.
        + Default: 
    + current_position: (string, optional) - User current position.
        + Default: 
    + previous_employee: (string, optional) - User previous employee.
        + Default: 
    + previous_position: (string, optional) - User previous position.
        + Default: 
    + experience_years: (string, optional) - Years of experience.
        + Default: 
    + current_salary: (float, optional) - Current salary.
        + Default: 
    + expected_salary: (float, optional) - Excepected salary.
        + Default: 
    + fb_id: (integer, optional) - The facebook id.
        + Default: 
    + google_id: (integer, optional) - The google account id.
        + Default: 
    + intgm_id: (integer, optional) - The instagram id.
        + Default: 

## Delete specified user [DELETE /api/v1/user/{username}]


+ Parameters
    + username: (string, required) - Username of specified user.

## List users Favorite restaurants [GET /api/v1/user/{username}/favorite-restaurants]


+ Parameters
    + username: (string, required) - The username for the specified user.
        + Default: 

## List users uploaded restaurants images [GET /api/v1/user/{username}/restaurants-images]


+ Parameters
    + username: (string, required) - The username for the specified user.
        + Default: 

## List users by their role [GET /api/v1/user/{role_name}]


+ Parameters
    + role_name: (string, required) - The name of role to list users by.
    ::: note
    The role name value must be one of the below values
     * super-admin
     * restaurant-manager
     *  restaurant-admin
     *  reservation-manager
     *  auditor
     *  auditor-of-auditors
     *  blogger-food-critics
     *  diner
    :::
    
        + Default: 

## Users Reservation [GET /api/v1/user/{user}/reservations]


+ Parameters
    + user: (string, required) - The name of role to list users by.
        + Default: 

## User Reviews [GET /api/v1/user/{username}/reviews]


+ Parameters
    + username: (string, required) - The id of review.

## Users Coupons [GET /api/v1/user/{user}/coupons]


+ Parameters
    + user: (string, required) - The name of role to list users by.
        + Default: 

## Manager Restaurant [GET /api/v1/user/manager-restaurant]


## Send Ad [POST /api/v1/user/send-ads-email]


+ Parameters
    + email: (email, optional) - if user not logged in it's not required.
        + Default: 
    + message: (text, required) - The body of mail.
        + Default: 

# Favorite Restaurants [/api/v1/user/{username}/fav-restaurant]
Favorite Restaurants.

## List Favorite Restaurants [GET /api/v1/user/{username}/fav-restaurant{?per_page,search,order,order_type}]


+ Parameters
    + username: (string, required) - The username of user.
        + Default: 10
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at, rate_value, user_rating]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Favorite Restaurant [POST /api/v1/user/{username}/fav-restaurant]


+ Parameters
    + username: (string, required) - The username of user.
        + Default: 10
    + restaurant_id[]: (array, required) - An array user favorite restaurant ids.
        + Default: 

## Delete Favorite Restaurant [DELETE /api/v1/user/{username}/fav-restaurant/{restaurant_id}]


+ Parameters
    + username: (string, required) - The username of user.
        + Default: 10
    + restaurant_id: (string, required) - The restaurant id.

# Claims [/api/v1/claim]
Claim presentation.

## Claim Owning Request [POST /api/v1/claim/{restaurant_slug}/claim/{user_id}]


+ Parameters
    + restaurant_slug: (string, required) - Restaurant Slug. the friendly url.
    + user_id: (integer, required) - Owner id.

# Restaurants [/api/v1/restaurant]
Restaurant Representation.

## List all restaurants [GET /api/v1/restaurant{?per_page,search,order,id,order_type,filters[price_from],filters[price_to],filters[distance][value],filters[distance][latitude],filters[distance][longitude],filters[category],filters[rating],filters[popularity],filters[type],filters[htr_stars]}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by [id, name, created_at,updated_at].
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
    + filters[rating_stars]: (integer, optional) - Filter results by rate value [1,2,3,4,5].
        + Default: 
    + filters[popularity]: (integer, optional) - Filter results by popularity.
        + Default: 
    + filters[city_id]: (integer, optional) - Filter results by city.
        + Default: 
    + filters[in_out_door]: (integer, required) - The Restaurant type in door or outdoor or both.
    Type                    | Key
    ------------------------|----
    In Door                 | 1
    Out Door                | 2
    Both                    | 3
    
        + Default: 
    + filters[type]: (integer, optional) - Filter results by type.
    Type                    | Key
    ------------------------|----
    RESTAURANTS             | 1
    CAFES                   | 2
    FINE_DINING             | 3
    BAKERIES_AND_PASTRIES   | 4
    
        + Default: restaurant
    + filters[htr_stars]: (integer, optional) - Filter results by type.
        + Default: 
    + filters[has_htr_stars]: (boolean, optional) - Return Restaurant with HTR Stars.
        + Default: 
    + filters[top_reviews]: (boolean, optional) - Filter restaurants by top reviews in the last month.
        + Default: 
    + filters[top_reservations]: (boolean, optional) - Filter Restaurants by top confirmed and paid reservations in last month.
        + Default: 
    + filters[is_reservable_online]: (boolean, optional) - Filter Restaurants by if they are available  to be reserved online or not.
        + Default: 
    + filters[is_trendy]: (boolean, optional) - Filter Restaurants by if they are have checked by restaurant admin as trendy.
        + Default: 
    + filters[allow_or_condition]: (boolean, optional) - Filter Restaurants by Or not and.
        + Default: 

## Create New Restaurant [POST /api/v1/restaurant]


+ Parameters
    + name: (string, required) - Restaurant Name.
        + Default: 
    + slug: (string, required) - URL friendly version of the restaurant title
        + Default: 
    + address: (string, required) - Restaurant address.
        + Default: 
    + city_id: (integer, required) - Id of city where restaurant located.
        + Default: 
    + latitude: (float, required) - The latitude of restaurant.
        + Default: 
    + longitude: (float, required) - The longitude of restaurant.
        + Default: 
    + phone: (integer, required) - Restaurant Phone.
        + Default: 
    + email: (string, required) - Restaurant Email.
        + Default: 
    + description: (string, required) - Restaurant Description.
        + Default: 
    + dress_code: (integer, required) - Restaurant Dress code.
        + Default: 
    + facebook: (string, required) - Restaurant Facebook page.
        + Default: 
    + twitter: (string, required) - Restaurant Twitter account.
        + Default: 
    + instagram: (string, required) - Restaurant page in instagaram.
        + Default: 
    + snap_chat: (string, required) - Restaurant page in snap chat.
        + Default: 
    + logo: (string, required) - Logo image of restaurant in base64 format.
        + Default: 
    + featured_image: (string, required) - Featured image in base 64 format.
        + Default: 
    + owner_id: (string, required) - The id for the restaurant owner.
        + Default: 
    + price_from: (float, required) - The start value of price range for restaurant.
        + Default: 
    + price_to: (float, required) - The end value of price range of restaurant.
        + Default: 
    + restaurant_managers[]: (array, required) - An array of users ids that should be the managers of restaurants.
        + Default: 
    + htr_stars: (integer, required) - the value of star that should the restaurant takes.
        + Default: 
    + in_out_door: (integer, required) - The Restaurant type in door or outdoor or both.
    Type                    | Key
    ------------------------|----
    In Door                 | 1
    Out Door                | 2
    Both                    | 3
    
        + Default: 
    + type: (integer, required) - Every created element must has a type of four types
    Type                    | Key
    ------------------------|----
    RESTAURANTS             | 1
    CAFES                   | 2
    FINE_DINING             | 3
    BAKERIES_AND_PASTRIES   | 4
    
        + Default: 
    + categories[]: (array, required) - An array of categories that restaurant belongs to.
        + Default: 
    + facilities[]: (array, required) - An array  of facilities the restaurant has.
        + Default: 

## View Restaurant information and details [GET /api/v1/restaurant/{restaurant_slug}]


+ Parameters
    + restaurant_slug: (string, required) - Restaurant Slug. the friendly url.
    + with_opening_days: (boolean, required) - When this flag is enabled Result will contains opening days.
    + with_reviews_count: (boolean, required) - When this flag is enabled Result will contains count of reviews.
    + with_branch_count: (boolean, required) - When this flag is enabled Result will contains count of branches.

## Update the specified Restaurant [PUT /api/v1/restaurant/slug]


+ Parameters
    + name: (string, optional) - Restaurant Name.
        + Default: 
    + slug: (string, optional) - URL friendly version of the restaurant title
        + Default: 
    + address: (string, optional) - Restaurant address.
        + Default: 
    + city_id: (integer, required) - Id of city where restaurant located.
        + Default: 
    + latitude: (float, optional) - The latitude of restaurant.
        + Default: 
    + longitude: (float, optional) - The longitude of restaurant.
        + Default: 
    + phone: (integer, optional) - Restaurant Phone.
        + Default: 
    + email: (string, optional) - Restaurant Email.
        + Default: 
    + description: (string, optional) - Restaurant Description.
        + Default: 
    + dress_code: (integer, optional) - Restaurant Dress code.
        + Default: 
    + facebook: (string, optional) - Restaurant Facebook page.
        + Default: 
    + twitter: (string, optional) - Restaurant Twitter account.
        + Default: 
    + instagram: (string, optional) - Restaurant page in instagaram.
        + Default: 
    + snap_chat: (string, required) - Restaurant page in snap chat.
        + Default: 
    + logo: (string, optional) - Logo image of restaurant in base64 format.
        + Default: 
    + featured_image: (string, optional) - Featured image in base 64 format.
        + Default: 
    + owner_id: (string, optional) - The id for the restaurant owner.
        + Default: 
    + price_from: (float, optional) - The start value of price range for restaurant.
        + Default: 
    + price_to: (float, optional) - The end value of price range of restaurant.
        + Default: 
    + restaurant_managers[]: (array, optional) - An array of users ids that should be the managers of restaurants.
        + Default: 
    + htr_stars: (integer, optional) - the value of star that should the restaurant takes.
        + Default: 
    + in_out_door: (integer, required) - The Restaurant type in door or outdoor or both.
    Type                    | Key
    ------------------------|----
    In Door                 | 1
    Out Door                | 2
    Both                    | 3
    
        + Default: 
    + type: (integer, required) - Every created element must has a type of four types
    Type                    | Key
    ------------------------|----
    RESTAURANTS             | 1
    CAFES                   | 2
    FINE_DINING             | 3
    BAKERIES_AND_PASTRIES   | 4
    
        + Default: 
    + categories[]: (array, optional) - An array of categories that restaurant belongs to.
        + Default: 
    + facilities[]: (array, required) - An array  of facilities the restaurant has.
        + Default: 

## Delete specified restaurant [DELETE /api/v1/restaurant/{restaurant_slug}]


+ Parameters
    + restaurant_slug: (string, required) - Restaurant Slug. the friendly url.

## Get Near By Restaurant [GET /api/v1/restaurant/nearby/{longitude}/{latitude}]


+ Parameters
    + longitude: (float, required) - longitude.
        + Default: 10
    + latitude: (float, required) - Latitude.
        + Default: 

## Request a Review [GET /api/v1/restaurant/{restaurant_id}/request-review/{user_id}]


+ Parameters
    + restaurant_id: (integer, required) - The restaurant id.
    + user_id: (integer, required) - The user id.

# Menu Items [/api/v1/restaurant/{restaurant_slug}/menu-item]
Restaurant Menu Items.

## List Menu Items [GET /api/v1/restaurant/{restaurant_slug}/menu-item{?per_page,search,order,order_type}]


+ Parameters
    + restaurant_slug: (integer, optional) - The short name of restaurant.
        + Default: 10
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at,]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC
    + dish_category: (boolean, optional) - Return Menu Items by dish Categories
        + Default: DESC
    + dish_category_id: (boolean, optional) - Return Menu Items by dish Categories id
        + Default: DESC

## Create Menu Item [POST /api/v1/restaurant/{restaurant_slug}/menu-item]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + name: (string, required) - The menu item name.
        + Default: 
    + slug: (string, required) - The menu item url friendly name
        + Default: 
    + image: (string, required) - The menu item photo in base64 format.
        + Default: 
    + price: (float, required) - Menu item price
        + Default: 
    + popular_dish: (boolean, required) - A flag if this menu item is a popular dish or not
        + Default: 
    + dish_category: (integer, required) - The type of dish
        + Default: 
    + description: (array, required) - Menu Item description.
        + Default: 

## Read Menu Item [GET /api/v1/restaurant/{restaurant_slug}/menu-item/{menu_item_slug}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + menu_item_slug: (string, required) - The menu item url friendly name.

## Update Menu Item [PUT /api/v1/restaurant/{restaurant_slug}/menu-item/{menu_item_slug}]


+ Parameters
    + menu_item_slug: (string, required) - The menu item url friendly name.
    + restaurant_slug: (string, required) - The restaurant slug.
    + name: (string, required) - The menu item name.
        + Default: 
    + slug: (string, required) - The menu item url friendly name
        + Default: 
    + image: (string, required) - The menu item photo in base64 format.
        + Default: 
    + price: (float, required) - Menu item price
        + Default: 
    + popular_dish: (boolean, required) - A flag if this menu item is a popular dish or not
        + Default: 
    + dish_category: (integer, required) - The type of dish
        + Default: 
    + description: (string, required) - Menu Item description.
        + Default: 

## Delete Menu Item [DELETE /api/v1/restaurant/{restaurant_slug}/menu-item/{menu_item_slug}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + menu_item_slug: (string, required) - The menu item url friendly name.

# Restaurants Gallery [/api/v1/restaurant/{restaurant_slug}/gallery]
Restaurant Gallery.

## List Gallery Items [GET /api/v1/restaurant/{restaurant_slug}/gallery{?per_page,search,order,order_type}]


+ Parameters
    + restaurant_slug: (integer, optional) - The short name of restaurant.
        + Default: 10

## Create Gallery [POST /api/v1/restaurant/{restaurant_slug}/gallery]


+ Parameters
    + restaurant_slug: (integer, optional) - The short name of restaurant.
        + Default: 10
    + name: (string, required) - The gallery name.
    + slug: (string, required) - The url friendly name.
        + Default: 
    + images[]: (array, required) - The gallery images as base64encode format
        + Default: 
    + description: (string, required) - The gallery description.
        + Default: 

## Read Gallery Files [GET /api/v1/restaurant/{restaurant_slug}/gallery/{image_id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + image_id: (string, required) - The id of image. of gallery

## Update likes, dislikes and share [GET /api/v1/restaurant/{restaurant_slug}/gallery/social-media/{image_id}/{action}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + image_id: (string, required) - The id of image. of gallery
    + action: (string, required) - The type of action [likes, dislikes, shares]

## Update Gallery [PUT /api/v1/restaurant/{restaurant_slug}/gallery/{review_id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + name: (string, required) - The gallery name.
    + slug: (string, required) - The url friendly name.
        + Default: 
    + images[]: (array, required) - The gallery images as base64encode format
        + Default: 
    + description: (string, required) - The gallery description.
        + Default: 

## Delete Gallery [DELETE /api/v1/restaurant/{restaurant_slug}/gallery/{gallery_slug}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + gallery_slug: (string, required) - The ulr friendly name of gallery.

# Photo Votes [/api/v1/file/{file_id}/vote]
Photo Votes.

## List File Votes [GET /api/v1/file/{file_id}/vote{?per_page,order,order_type}]


+ Parameters
    + file_id: (integer, optional) - The photo id.
        + Default: 10
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + order: (string, optional) - Column name to order results by.[id, created_at, rate_value, user_rating]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Vote [POST /api/v1/file/{file_id}/vote]


+ Parameters
    + file_id: (integer, required) - The photo id.
    + vote_up: (boolean, required) - 1 for true and 0 for false.
    + vote_down: (boolean, required) - 1 for true and 0 for false.

## Show Vote [GET /api/v1/file/{file_id}/vote/{vote_id}]


+ Parameters
    + file_id: (integer, required) - The photo id.
    + vote_id: (integer, required) - The id of vote.

## Update Vote [PUT /api/v1/file/{file_id}/vote/{vote_id}]


+ Parameters
    + file_id: (integer, required) - The photo id.
    + vote_id: (integer, required) - The id of vite.
    + vote_up: (boolean, required) - 1 for true and 0 for false.
    + vote_down: (boolean, required) - 1 for true and 0 for false.

## Delete Vote [DELETE /api/v1/file/{file_id}/vote/{vote_id}]


+ Parameters
    + file_id: (integer, required) - The photo id.
    + vote_id: (integer, required) - The id of vote.

# Photo Comments [/api/v1/file/{file_id}/comment]
Photo Comments.

## List Photo comments [GET /api/v1/file/{file_id}/comment{?per_page,order,order_type}]


+ Parameters
    + file_id: (integer, optional) - The photo id.
        + Default: 10
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + order: (string, optional) - Column name to order results by.[id, created_at, rate_value, user_rating]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Comment [POST /api/v1/file/{file_id}/comment]


+ Parameters
    + file_id: (integer, required) - The photo id.
    + comment: (string, required) - The comment body.

## Show Comment [GET /api/v1/file/{file_id}/comment/{comment_id}]


+ Parameters
    + file_id: (integer, required) - The photo id.
    + comment_id: (integer, required) - The id of comment.

## Update Comment [PUT /api/v1/file/{file_id}/comment/{comment_id}]


+ Parameters
    + file_id: (integer, required) - The photo id.
    + comment_id: (integer, required) - The id of comment.
    + comment: (string, required) - The comment body.

## Delete Comment [DELETE /api/v1/file/{file_id}/comment/{comment_id}]


+ Parameters
    + file_id: (integer, required) - The photo id.
    + comment_id: (integer, required) - The id of comment.

# Restaurants Reviews [/api/v1/restaurant/{restaurant_slug}/rates]
Review presentation.

## List Reviews [GET /api/v1/restaurant/{restaurant_slug}/rates{?per_page,search,order,order_type}]


+ Parameters
    + restaurant_slug: (integer, optional) - The short name of restaurant.
        + Default: 10
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at, rate_value, user_rating]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Review [POST /api/v1/restaurant/{restaurant_slug}/rates]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + title: (string, required) - The review title.
        + Default: 
    + description: (string, required) - The review body
        + Default: 
    + last_visit_date: (string, required) - The last date for restaurant.
        + Default: 
    + seen: (boolean, required) - The seen flag is mainly by restaurant manageer to check that the review has been seen.
        + Default: 
    + rate_value[]: (array, required) - An array contains the values of rates value must be 1 and 5
        + Default: 
    + type[]: (array, required) - An array contains the type of rates.
    
    MUSIC = 1,
    LOOKS_OF_RESTAURANT = 2
    ACCESSIBILITY = 3
    TEMPERATURE = 4
    TASTE = 5
    CLEAN_FLOORING = 6
    CLEAN_TABLES = 7
    CLEAN_ENVIRONMENT = 8
    DOOR_GREETING = 9
    WAITER_FRIENDLINESS = 10
    SPEED_OF_SERVICE = 11
    WAITERS_KNOWLEDGE_OF_MENU = 12
    PRESENTATION = 13
    
    
        + Default: 

## Read Review [GET /api/v1/restaurant/{restaurant_slug}/rates/{review_id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + review_id: (string, required) - The id of review.

## Update Review [PUT /api/v1/restaurant/{restaurant_slug}/rates/{review_id}]


+ Parameters
    + review_id: (integer, required) - The id of review.
        + Default: 
    + restaurant_slug: (string, required) - The restaurant slug.
    + title: (string, required) - The review title.
        + Default: 
    + description: (string, required) - The review body
        + Default: 
    + last_visit_date: (string, required) - The last date for restaurant.
        + Default: 
    + rate_value[]: (array, required) - An array contains the values o f rates
        + Default: 
    + type[]: (array, required) - An array contains the type of rates.
        + Default: 

## Delete Review [DELETE /api/v1/restaurant/{restaurant_slug}/rates/{review_id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + review_id: (string, required) - The id of review.

# Reports [/report]
Reports API Representation

## Display a listing of the Reports. [GET /report/{per_page,search,order,id,order_type}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages . 
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by (name - id - email).
        + Default: id
    + order_type: (string, optional) - Type of sorting (Descending - Ascending).
        + Default: desc

## Display a listing of the Reports. [GET /report/{report_type}/{reported_id}]


+ Parameters
    + report_type: (integer, required) - type of Three types 1 => Restaurant , 2 => Review, 3 => Photo
        + Default: 3
    + reported_id: (integer, required) - id of item to be reported
        + Default: 

## Create New Report [POST /report]


+ Parameters
    + report_type: (string, required) - Every created element must has a type of Three types Restaurant => Restaurant , Review => Review, Photo => Photo
        + Default: 
    + report_subject: (string, required) - Every created element must has a type of Three types Spam => Spam , Offensive Language => Offensive Language, Wrong Restaurant => Wrong Restaurant , Irrelevant => Irrelevant
        + Default: 
    + reported_id: (integer, required) - ID of the Reported item
        + Default: 
    + user_id: (integer, required) - ID of the User make the report
        + Default: 
    + details: (string, optional) - Report details
        + Default: 

## View User information and details [GET /report/{id}]


+ Parameters
    + id: (integer, required) - ID of specified report.

## Update the specified Report [PUT /report/id]


+ Parameters
    + report_type: (string, required) - Every created element must has a type of Three types Restaurant => Restaurant , Review => Review, Photo => Photo
        + Default: 
    + report_subject: (string, required) - Every created element must has a type of Three types Spam => Spam , Offensive Language => Offensive Language, Wrong Restaurant => Wrong Restaurant , Irrelevant => Irrelevant
        + Default: 
    + reported_id: (integer, required) - ID of the Reported item
        + Default: 
    + user_id: (integer, required) - ID of the User make the report
        + Default: 
    + details: (string, optional) - Report details
        + Default: 

## Delete specified report [DELETE /report/{id}]


+ Parameters
    + id: (integer, required) - Report Id 

# Reservation Policies [/api/v1/restaurant/{restaurant_slug}/reservation-policy]
Restaurant Reservation Policies.

## List Reservation Policies [GET /api/v1/restaurant/{restaurant_slug}/reservation-policy{?per_page,search,order,order_type}]


+ Parameters
    + restaurant_slug: (integer, optional) - The short name of restaurant.
        + Default: 10
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at,]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Reservation Policy [POST /api/v1/restaurant/{restaurant_slug}/reservation-policy]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + name: (string, required) - The menu item name.
        + Default: 
    + slug: (string, required) - The menu item url friendly name
        + Default: 
    + image: (string, required) - The menu item photo in base64 format.
        + Default: 
    + price: (float, required) - Menu item price
        + Default: 
    + popular_dish: (boolean, required) - A flag if this menu item is a popular dish or not
        + Default: 
    + dish_category: (integer, required) - The type of dish
        + Default: 
    + description: (array, required) - Reservation Policy description.
        + Default: 

## Read Reservation Policy [GET /api/v1/restaurant/{restaurant_slug}/reservation-policy/{reservation_policy_id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + reservation_policy_id: (string, required) - The menu item url friendly name.

## Update Reservation Policy [PUT /api/v1/restaurant/{restaurant_slug}/reservation-policy/{reservation_policy_id}]


+ Parameters
    + menu_item_slug: (string, required) - The menu item url friendly name.
    + restaurant_slug: (string, required) - The restaurant slug.
    + name: (string, required) - The menu item name.
        + Default: 
    + slug: (string, required) - The menu item url friendly name
        + Default: 
    + image: (string, required) - The menu item photo in base64 format.
        + Default: 
    + price: (float, required) - Menu item price
        + Default: 
    + popular_dish: (boolean, required) - A flag if this menu item is a popular dish or not
        + Default: 
    + dish_category: (integer, required) - The type of dish
        + Default: 
    + description: (string, required) - Reservation Policy description.
        + Default: 

## Delete Reservation Policy [DELETE /api/v1/restaurant/{restaurant_slug}/reservation-policy/{reservation_policy_id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + reservation_policy_id: (string, required) - The menu item url friendly name.

# Restaurants Branches [/api/v1/restaurant/{restaurant_slug}/branch]
Branch presentation.

## List Branches [GET /api/v1/restaurant/{restaurant_slug}/branch{?per_page,search,order,order_type}]


+ Parameters
    + restaurant_slug: (string, required) - The short name of restaurant.
        + Default: 10
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Branch [POST /api/v1/restaurant/{restaurant_slug}/branch]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + slug: (string, required) - The branch slug.
    + latitude: (string, required) - The Branch latitude.
        + Default: 
    + longitude: (string, required) - The Branch longitude.
        + Default: 
    + email: (string, required) - The Branch email.
        + Default: 
    + phone: (string, required) - The Branch phone.
        + Default: 
    + I18N[locale][address]: (array, required) - An array contains the values of translation of address
        + Default: 

## Read Branch [GET /api/v1/restaurant/{restaurant_slug}/branch/{id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + slug: (string, required) - The branch slug.
    + id: (integer, required) - The ID of review.

## Update Review [PUT /api/v1/restaurant/{restaurant_slug}/branch/{slug}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + latitude: (string, required) - The Branch latitude.
        + Default: 
    + longitude: (string, required) - The Branch longitude.
        + Default: 
    + email: (string, required) - The Branch email.
        + Default: 
    + phone: (string, required) - The Branch phone.
        + Default: 
    + I18N[locale][address]: (array, required) - An array contains the values of translation of address
        + Default: 

## Delete specified branch [DELETE /api/v1/restaurant/{restaurant_slug}/branch/{id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + id: (integer, required) - Branch Id 

# Reservations [/api/v1/reservation]
Class ApiReservationController

## POST


+ Parameters
    + user_id: (Integer, required) - 
    + restaurant_id: (Integer, required) - 
    + date: (Date, required) - 
    + time: (Time, required) - 
    + number_of_people: (Integer, required) - 
    + advance_payment: (Boolean, required) - 
    + coupon_code: (String, required) - 

## User Story:
As a reservation manager
I want to approve a reservation made by a diner
so that he can dine in [POST /api/v1/reservation/{reservation_id}/accept]


+ Parameters
    + reservation_id: (integer, required) - 

## User Story:
As a reservation manager
I want to change the reservation made by a diner
that he arrived [POST /api/v1/reservation/{reservation_id}/arrived]


+ Parameters
    + reservation_id: (integer, required) - 

## The following procedures does three things:
Validate that a reservation is still pendning
Reject a reservation
Fire Notification [POST /api/v1/reservation/{reservation_id}/reject]


## Display the specified resource. [GET /api/v1/reservation/{reservation_id}]


+ Parameters
    + reservation_id: (Integer, required) - 

# Opening Days [/api/v1/restaurant/{restaurant_slug}/opening-days]
Opening Days.

## List Opening Days [GET /api/v1/restaurant/{restaurant_slug}/opening-days/   {?per_page,search,order,order_type}]


+ Parameters
    + restaurant_slug: (integer, optional) - The short name of restaurant.
        + Default: 10
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + as_string: (string, optional) - Return days as string .
        + Default: 0
    + order: (string, optional) - Column name to order results by.[id, created_at, rate_value, user_rating]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Opening day [POST /api/v1/restaurant/{restaurant_slug}/opening-days]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + day_name: (string, required) - The restaurant slug.
    + from: (string, required) - The review title.
        + Default: 
    + to: (string, required) - The review body
        + Default: 
    + status: (string, required) - The last date for restaurant.
        + Default: 

## Show Opening Day [GET /api/v1/restaurant/{restaurant_slug}/opening-days/{opening_day_id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + opening_day_id: (string, required) - The id of opening Day.

## Update Opening Day [PUT /api/v1/restaurant/{restaurant_slug}/opening-days/{opening_day_id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + day_name: (string, required) - The restaurant slug.
    + from: (string, required) - The review title.
        + Default: 
    + to: (string, required) - The review body
        + Default: 
    + status: (string, required) - The last date for restaurant.
        + Default: 
    + opening_day_id: (string, required) - The id of opening Day.

## Delete Opening Day [DELETE /api/v1/restaurant/{restaurant_slug}/opening-days/{opening_day_id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + review_id: (string, required) - The id of review.

# Review Reply [/api/v1/review/{review_id}/reply]
Review Reply.

## Create Reply [POST /api/v1/review/{review_id}/reply]


+ Parameters
    + title: (string, required) - The reply title.
    + comment: (string, required) - The reply body.
    + review_id: (string, required) - The review id.

## Show Reply [GET /api/v1/review/{review_id}/reply/{reply_id}]


+ Parameters
    + review_id: (string, required) - The review id.
    + restaurant_slug: (string, required) - The restaurant slug.
    + reply_id: (string, required) - The id of reply.

## Update Reply [PUT /api/v1/review/{review_id}/reply/{reply_id}]


+ Parameters
    + review_id: (string, required) - The review id.
    + reply_id: (string, required) - The reply id.
    + title: (string, required) - The reply title.
    + comment: (string, required) - The reply body.

## Delete reply [DELETE /api/v1/review/{review_id}/reply/{reply_id}]


+ Parameters
    + restaurant_slug: (string, required) - The restaurant slug.
    + review_id: (string, required) - The review id.
    + reply_id: (string, required) - The id of reply.

# Categories [/api/v1/category]
Categories.

## List Categories [GET /api/v1/category{?per_page,search,order,order_type}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at, rate_value, user_rating]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Category [POST /api/v1/category]


+ Parameters
    + category_name: (string, required) - The category name.
        + Default: 

## Read Category [GET /api/v1/category/{id}]


+ Parameters
    + id: (integer, required) - The category id.
        + Default: 
    + category_name: (string, required) - The category id.

## Update Category [PUT /api/v1/category/{id}]


+ Parameters
    + id: (integer, required) - The category id.
        + Default: 
    + category_name: (string, required) - The category name.
        + Default: 

## Delete Category [DELETE /api/v1/category/{id}]


+ Parameters
    + id: (integer, required) - The category id.
        + Default: 

# Cities [/api/v1/city]
Cities.

## List Cities [GET /api/v1/city{?per_page,search,order,order_type}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at, city_name]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create City [POST /api/v1/city]


+ Parameters
    + city_name: (string, required) - The city name.
        + Default: 

## Read City [GET /api/v1/city/{id}]


+ Parameters
    + id: (integer, required) - The city id.
        + Default: 
    + city_name: (string, required) - The city id.

## Update City [PUT /api/v1/city/{id}]


+ Parameters
    + id: (integer, required) - The city id.
        + Default: 
    + city_name: (string, required) - The city name.
        + Default: 

## Delete City [DELETE /api/v1/city/{id}]


+ Parameters
    + id: (integer, required) - The city id.
        + Default: 

# Dish Categories [/api/v1/dish-category]
Dish Categories.

## List Dish Categories [GET /api/v1/dish-category{?per_page,search,order,order_type}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at, rate_value, user_rating]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC
    + restaurant_id: (integer, optional) - Filter dish categories by restaurant.
        + Default: DESC

## Create Dish Category [POST /api/v1/dish-category]


+ Parameters
    + category_name: (string, required) - The category name.
        + Default: 

## Read Dish Category [GET /api/v1/dish-category/{id}]


+ Parameters
    + id: (integer, required) - The category id.
        + Default: 
    + category_name: (string, required) - The category id.

## Update Dish Category [PUT /api/v1/dish-category/{id}]


+ Parameters
    + id: (integer, required) - The category id.
        + Default: 
    + category_name: (string, required) - The category name.
        + Default: 

## Delete Dish Category [DELETE /api/v1/dish-category/{id}]


+ Parameters
    + id: (integer, required) - The category id.
        + Default: 

# Facilities [/api/v1/facility]
Facilities

## List Facilities [GET /api/v1/facility{?per_page,search,order,order_type}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at, rate_value, user_rating]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Facility [POST /api/v1/facility/{id}]


+ Parameters
    + id: (integer, required) - The facility id.
        + Default: 
    + name: (string, required) - The facility name.
        + Default: 
    + description: (string, required) - The facility description.
        + Default: 

## Read Facility [GET /api/v1/facility/{id}]


+ Parameters
    + id: (integer, required) - The facility id.
        + Default: 

## Update Facility [PUT /api/v1/facility/{id}]


+ Parameters
    + id: (integer, required) - The facility id.
        + Default: 
    + name: (string, required) - The facility name.
        + Default: 
    + description: (string, required) - The facility description.
        + Default: 

## Delete Facility [DELETE /api/v1/facility/{id}]


+ Parameters
    + id: (integer, required) - The facility id.
        + Default: 

# Job Titles [/api/v1/job-title]
Jobs Titles.

## List Jobs Titles [GET /api/v1/job-title{?per_page,search,order,order_type}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at, rate_value, user_rating]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Job Title [POST /api/v1/job-title]


+ Parameters
    + job_title: (string, required) - The title of job .
        + Default: 
    + description: (string, required) - The job title description.
        + Default: 

## Read Job Title [GET /api/v1/job-title/{id}]


+ Parameters
    + id: (integer, required) - The job title id.
        + Default: 

## Update Job Title [PUT /api/v1/job-title/{id}]


+ Parameters
    + id: (integer, required) - The job title id.
        + Default: 
    + job_title: (string, required) - The title of job.
        + Default: 

## Delete Job Title [DELETE /api/v1/job-title/{id}]


+ Parameters
    + id: (integer, required) - The job title id.
        + Default: 

# Job Vacancies [/api/v1/restaurant/{restaurant_slug}/job-vacancy]
Jobs Vacancies.

## List Jobs Vacancies [GET /api/v1/restaurant/{restaurant_slug}/job-vacancy{?per_page,search,order,order_type}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at, rate_value, user_rating]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC
    + restaurant_slug: (string, required) - The slug name of restaurant.
        + Default: DESC

## Apply to Job [POST /api/v1/restaurant/{restaurant_slug}/job-vacancy/apply-tot-job]


+ Parameters
    + job_id: (integer, required) - The title of job .
        + Default: 
    + user_id: (integer, required) - The id of user applied to the job.
        + Default: 
    + status: (boolean, required) - The status of job.
        + Default: 
    + restaurant_slug: (string, required) - The slug name of restaurant.
        + Default: DESC

## List Users applied to jobs [POST /api/v1/restaurant/{restaurant_slug}/job-vacancy/apply-tot-job]


+ Parameters
    + job_id: (integer, required) - The title of job .
        + Default: 
    + user_id: (integer, required) - The id of user applied to the job.
        + Default: 
    + status: (boolean, required) - The status of job.
        + Default: 
    + restaurant_slug: (string, required) - The slug name of restaurant.
        + Default: DESC

## Create Job Vacancy [POST /api/v1/restaurant/{restaurant_slug}/job-vacancy]


+ Parameters
    + job_title_id: (integer, required) - The title of job .
        + Default: 
    + description: (string, required) - The job title description.
        + Default: 
    + status: (boolean, required) - The status of job.
        + Default: 
    + restaurant_slug: (string, required) - The slug name of restaurant.
        + Default: DESC

## Read Job Vacancy [GET /api/v1/restaurant/{restaurant_slug}/job-vacancy/{id}]


+ Parameters
    + id: (integer, required) - The job title id.
        + Default: 
    + restaurant_slug: (string, required) - The slug name of restaurant.
        + Default: DESC

## Update Job Vacancy [PUT /api/v1/restaurant/{restaurant_slug}/job-vacancy/{id}]


+ Parameters
    + job_title_id: (integer, required) - The title of job .
        + Default: 
    + description: (string, required) - The job title description.
        + Default: 
    + status: (boolean, required) - The status of job.
        + Default: 
    + restaurant_slug: (string, required) - The slug name of restaurant.
        + Default: DESC

## Delete Job Vacancy [DELETE /api/v1/restaurant/{restaurant_slug}/job-vacancy/{id}]


+ Parameters
    + id: (integer, required) - The job title id.
        + Default: 
    + restaurant_slug: (string, required) - The slug name of restaurant.
        + Default: DESC

# Admin Reviews [/api/v1/admin-review]
Admin Reviews.

## List Admin Reviews [GET /api/v1/admin-review{?per_page,search,order,order_type}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at,]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Admin Review [POST /api/v1/admin-review]


+ Parameters
    + restaurant_name: (string, required) - The admin review name.
        + Default: 
    + images: (string, required) - The admin review photo in base64 format.
        + Default: 
    + description: (string, required) - Admin Review description.
        + Default: 

## Read Admin Review [GET /api/v1/admin-review/{id}]


+ Parameters
    + id: (string, required) - The admin review id.

## Update Admin Review [PUT /api/v1/admin-review/{id}]


+ Parameters
    + id: (string, required) - The admin review url friendly name.
    + restaurant_slug: (string, required) - The restaurant slug.
    + name: (string, required) - The admin review name.
        + Default: 
    + slug: (string, required) - The admin review url friendly name
        + Default: 
    + image: (string, required) - The admin review photo in base64 format.
        + Default: 
    + price: (float, required) - Menu item price
        + Default: 
    + popular_dish: (boolean, required) - A flag if this admin review is a popular dish or not
        + Default: 
    + dish_category: (integer, required) - The type of dish
        + Default: 
    + description: (string, required) - Admin Review description.
        + Default: 

## Delete Admin Review [DELETE /api/v1/admin-review/{id}]


+ Parameters
    + id: (string, required) - The admin review id.

# Movies [/api/v1/movie]
Movies.

## List Movies [GET /api/v1/movie{?per_page,search,order,order_type}]


+ Parameters
    + per_page: (integer, optional) - Number of items per pages.
        + Default: 10
    + search: (string, optional) - A search query.
        + Default: 
    + order: (string, optional) - Column name to order results by.[id, created_at, rate_value, user_rating]
        + Default: id
    + order_type: (string, optional) - Type of sorting (DESC - ASC).
        + Default: DESC

## Create Movie [POST /api/v1/movie]


+ Parameters
    + name: (string, required) - The movie name.
        + Default: 
    + description: (string, required) - The movie description.
        + Default: 
    + poster: (base64, required) - The movie poster in base63 format.
        + Default: 
    + booking_url: (string, required) - The widget from cinemawy.com url.
        + Default: 
    + enable_booking: (boolean, required) - a flag to enable or disable booking.
        + Default: 

## Read Movie [GET /api/v1/movie/{id}]


+ Parameters
    + id: (integer, required) - The movie id.
        + Default: 

## Update Movie [PUT /api/v1/movie/{id}]


+ Parameters
    + id: (integer, required) - The movie id.
        + Default: 
    + name: (string, required) - The movie name.
        + Default: 
    + description: (string, required) - The movie description.
        + Default: 
    + poster: (base64, required) - The movie poster in base64 format.
        + Default: 
    + booking_url: (string, required) - The widget from cinemawy.com url.
        + Default: 
    + enable_booking: (boolean, required) - a flag to enable or disable booking.
        + Default: 
    + add_to_featured: (string, required) - A flag to enable adding featured image to slider.
        + Default: 
    + movie_featured_image: (boolean, required) - The featured image in base64 format.
        + Default: 

## Delete Movie [DELETE /api/v1/movie/{id}]


+ Parameters
    + id: (integer, required) - The movie id.
        + Default: