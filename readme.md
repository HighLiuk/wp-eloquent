# WP Eloquent

The WP Eloquent is a complete toolbox providing an ORM and schema generator. It supports MySQL, Postgres, SQL Server, and SQLite. It translates WordPress tables into [Eloquent-compatible models](https://laravel.com/docs/10.x/eloquent).

No need to use the old WP_Query class anymore, we enter the world of the future by producing readable and reusable code! Additional features are also available for a custom WordPress experience.

The library ensuring compatibility with Eloquent, you can consult the [documentation of the ORM](https://laravel.com/docs/10.x/eloquent) if you are a little lost :)

## Installation

The recommended installation method is [Composer](https://getcomposer.org/).

```bash
composer require highliuk/wp-eloquent
```

<!-- Eng -->

## Setup

The connection to the database (via $ wpdb) is made on the first call of an Eloquent model.
If you need to retrieve the connection instance, simply run the following code (prefer the use of `use`):

```php
HighLiuk\Eloquent\Database::instance();
```

## Supported models

### Posts

```php
use HighLiuk\Eloquent\Model\Post;

// Get the post with ID 1
$post = Post::find(1);

// Related data available
$post->author;
$post->comments;
$post->terms;
$post->tags;
$post->categories;
$post->meta;
```

**_Status_**

By default, `Post` returns all posts regardless of their status. This can be overridden via a [local scope](https://laravel.com/docs/10.x/eloquent#query-scopes) `published` to return only published posts.

```php
Post::published()->get();
```

It is also possible to define the status in question via the [local scope](https://laravel.com/docs/10.x/eloquent#query-scopes#query-scopes) `status`.

```php
Post::status('draft')->get();
```

**_Post Types_**

By default, `Post` returns all content types. This can be overridden via the [local scope](https://laravel.com/docs/10.x/eloquent#query-scopes#query-scopes) `type`.

```php
Post::type('page')->get();
```

### Comments

```php
use HighLiuk\Eloquent\Model\Comment;

// Get the comment with ID 12345
$comment = Comment::find(12345);

// Related data available
$comment->post;
$comment->author;
$comment->meta;
```

### Terms

In this version `Term` is accessible as a model but is only accessible through an article. However, just extend `Term` to apply it to other custom content types.

```php
$post->terms()->where('taxonomy', 'country');
```

### Users

```php
use HighLiuk\Eloquent\Model\User;

// All users
$users = User::get();

// Get the user with ID 123
$user = User::find(123);
```

### Options

In WordPress, options retrieval is done with the `get_option` function. With Eloquent, to avoid unnecessary loading of the WordPress Core, you can use the `get` function of the `Option` model.

```php
$siteUrl = Option::get('siteurl');
```

You can also add other options:

```php
Option::add('foo', 'bar'); // stored as a string
Option::add('baz', ['one' => 'two']); // the array will be serialized
```

You can retrieve all options as an array (watch out for performance...):

```php
$options = Option::asArray();
echo $options['siteurl'];
```

You can also specify the specific options to retrieve:

```php
$options = Option::asArray(['siteurl', 'home', 'blogname']);
echo $options['home'];
```

### Menus

To retrieve a menu from its alias, use the syntax below. The menu items will be returned in an `items` variable (it's a collection of `HighLiuk\Eloquent\Model\MenuItem` objects).

The menu types currently supported are: Pages, Posts, Custom Links and Categories.

Once you have the `MenuItem` model, if you want to use the original instance (such as Page or Term, for example), just call the `MenuItem::instance()` method. The `MenuItem` object is just a post whose `post_type` is equal to `nav_menu_item`:

```php
$menu = Menu::slug('primary')->first();

foreach ($menu->items as $item) {
    echo $item->instance()->title; // if it's a Post
    echo $item->instance()->name; // if it's a Term
    echo $item->instance()->link_text; // if it's a Custom Link
}
```

The `instance()` method will return the corresponding objects:

- `Post` instance for a menu item of type `post`;
- `Page` instance for a menu item of type `page`;
- `CustomLink` instance for a menu item of type `custom`;
- `Term` instance for a menu item of type `category`.

#### Multi-levels Menus

To manage multi-level menus, you can iterate to place them at the right level, for example.

You can use the `MenuItem::parent()` method to retrieve the parent instance of the menu item:

```php
$items = Menu::slug('foo')->first()->items;
$parent = $items->first()->parent(); // Post, Page, CustomLink or Term (category)
```

To group the menus by parent, you can use the `->groupBy()` method in the `$menu->items` collection, which will group the items according to their parent (`$item->parent()->ID`).

For more information on the `groupBy()` method, [see the Eloquent documentation](https://laravel.com/docs/10.x/collections#method-groupby).

## Custom Fields

The `Post` model supports aliases, so if you inspect a `Post` object you may find aliases in the static array `$aliases` (such as `title` for `post_title` and `content` for `post_content`.

```php
$post = Post::find(1);
$post->title === $post->post_title; // true
```

You can extend the `Post` model to create your own. Just add your aliases to the extended model, it will automatically inherit those defined in the `Post` model:

```php
class A extends \HighLiuk\Eloquent\Model\Post
{
    protected static $aliases = [
        'foo' => 'post_foo',
    ];
}

$a = A::find(1);
echo $a->foo;
echo $a->title; // retrieved from the Post model
```

## Custom Scopes

To order `Post` or `User` models, you can use the `newest()` and `oldest()` scopes:

```php
$newest = Post::newest()->first();
$oldest = Post::oldest()->first();
```

## Pagination

To paginate the results, simply use the `paginate()` method of Eloquent:

```php
// Display posts with 5 items per page
$posts = Post::published()->paginate(5);
foreach ($posts as $post) {
    // ...
}
```

To display the pagination links, use the `links()` method:

```php
{{ $posts->links() }}
```

## Meta

The Eloquent model set includes a WordPress metadata management.

Here is an example to retrieve metadata:

```php
// Retrieves a meta (here 'link') from the Post model (we could have used another model like User)
$post = Post::find(31);
echo $post->meta->link; // OR
echo $post->fields->link;
echo $post->link; // OR
```

To create or update a user's metadata, just use the `saveMeta()` or `saveField()` methods. They return a boolean like the `save()` method of Eloquent.

```php
$post = Post::find(1);
$post->saveMeta('username', 'highliuk');
```

It is possible to save multiple metadata in a single call:

```php
$post = Post::find(1);
$post->saveMeta([
    'username' => 'highliuk',
    'url' => 'https://github.com/HighLiuk',
]);
```

The library also puts the methods `createMeta()` and `createField()`, which work how the `saveX()` methods, but they are only used for creation and return the object of type `PostMeta` created by the instance, instead of a boolean.

```php
$post = Post::find(1);
$postMeta = $post->createMeta('foo', 'bar'); // instance of PostMeta class
$trueOrFalse = $post->saveMeta('foo', 'baz'); // boolean
```

## Query a Post from a custom field (Meta)

There are different ways to query from a meta-data (meta) using scopes on a `Post` model (or any other model using the `HasMetaFields` trait):

To check if a meta-data exists, use the `hasMeta()` scope:

```php
// Retrieves the first article with the meta "featured_article"
$post = Post::published()->hasMeta('featured_article')->first();
```

To check if a meta-data exists and has a specific value, use the `hasMeta()` scope with a value.

```php
// Retrieves the first article with the meta "username" and having the value "highliuk"
$post = Post::published()->hasMeta('username', 'highliuk')->first();
```

It is also possible to perform a query by defining multiple meta-data and multiple associated values by passing an array of value to the `hasMeta()` scope:

```php
$post = Post::hasMeta(['username' => 'highliuk'])->first();
$post = Post::hasMeta(['username' => 'highliuk', 'url' => 'highliuk.fr'])->first();
// Or just by providing the meta-data keys
$post = Post::hasMeta(['username', 'url'])->first();
```

If you need to match a case-insensitive string or a match with wildcard characters, you can use the `hasMetaLike()` scope with a value. This will use the SQL `LIKE` operator, so it is important to use the generic operator '%'.

```php
// Will match: 'B Gosselet', 'B BOSSELET', and 'b gosselet'.
$post = Post::published()->hasMetaLike('author', 'B GOSSELET')->first();

// Using the % operator, the following results will be returned: 'N Leroy', 'N LEROY', 'n leroy', 'Nico Leroy' etc.
$post = Post::published()->hasMetaLike('author', 'N%Leroy')->first();
```

## Images

Retrieving an image from a `Post` or `Page` model.

```php
$post = Post::find(1);

// Retrieves an instance of HighLiuk\Eloquent\Model\Meta\ThumbnailMeta.
print_r($post->thumbnail);

// You must display the image instance to retrieve the url of the original image
echo $post->thumbnail;
```

To retrieve a specific image size, use the `->size()` method on the object and enter the size alias in the parameter (ex. `thumbnail` or `medium`). If the thumbnail has been generated, the method returns an object with the meta-data, otherwise, it is the original url that is returned (WordPress behavior).

```php
if ($post->thumbnail !== null) {
    /**
     * [
     *     'file' => 'filename-300x300.jpg',
     *     'width' => 300,
     *     'height' => 300,
     *     'mime-type' => 'image/jpeg',
     *     'url' => 'http://localhost/wp-content/uploads/filename-300x300.jpg',
     * ]
     */
    print_r($post->thumbnail->size(HighLiuk\Eloquent\Model\Meta\ThumbnailMeta::SIZE_THUMBNAIL));

    // http://localhost/wp-content/uploads/filename.jpg
    print_r($post->thumbnail->size('invalid_size'));
}
```

## Advanced Custom Fields

The library makes available almost all ACF fields (with the exception of the Google Map field). It allows you to retrieve the fields in an optimal way without going through the ACF module.

### Basic usage

To retrieve a value from a field, simply initialize a model of type `Post` and invoke the custom field:

```php
$post = Post::find(1);
echo $post->acf->website_url; // returns the url provided in a field with the key website_url
```

### Performance

When using `$post->acf->website_url`, additional requests are executed to retrieve the field according to the ACF approach. It is possible to use a specific method to avoid these additional requests. Simply enter the custom content type used as a function:

```php
// The method performing additional requests
echo $post->acf->author_username; // it's a field relative to User

// Without additional request
echo $post->acf->user('author_username');

// Other examples without requests
echo $post->acf->text('text_field_name');
echo $post->acf->boolean('boolean_field_name');
```

> PS: The method must be called in camel case format. For example, for the `date_picker` type field you must write `$post->acf->datePicker('fieldName')`. The library converts camel case to snake case for you.

## Create a table

Docs to come

## Advanced queries

The library being compatible with Eloquent, you can without problem perform complex queries without taking into account the WordPress context.

For example, to retrieve customers whose age is greater than 40 years old:

```php
$users = Capsule::table('customers')->where('age', '>', 40)->get();
```

## Custom models

### Definition of the Eloquent model

To add your own method to an existing model, you can perform "extends" of this model. For example, for the `User` model, you could produce the following code:

```php
namespace App\Model;

use \HighLiuk\Eloquent\Model\User as BaseUser;

class User extends BaseUser {

    public function orders() {
        return $this->hasMany('\App\Model\User\Orders');
    }

    public function current() {
        // fonctionnalité spécifique à l'utilisateur courant
    }

    public function favorites() {
        return $this->hasMany('Favorites');
    }

}
```

Another example would be to define a new taxonomy to an article, for example `country`

```php
namespace App\Model;

user \HighLiuk\Eloquent\Model\Post as BasePost;

class Post extends BasePost {
    public function countries() {
        return $this->terms()->where('taxonomy', 'country');
    }
}

Post::with(['categories', 'countries'])->find(1);
```

To access the model of a new content type, here is an example of what could be proposed:

```php
namespace App\Model;

class CustomPostType extends \HighLiuk\Eloquent\Model\Post {
    protected $post_type  = 'custom_post_type';

    public static function getBySlug(string $slug): self
    {
        return self::where('post_name', $slug)->firstOrfail();
    }
}

CustomPostType::with(['categories', 'countries'])->find(1);
```

<!-- Eng -->

### Queries on custom models

It is also possible to work with custom content types. You can use the `type(string)` method or create your own classes:

```php
// using the type() method
$videos = Post::type('video')->status('publish')->get();

// by defining its own class
class Video extends HighLiuk\Eloquent\Model\Post
{
    protected $postType = 'video';
}

$videos = Video::status('publish')->get();
```

By using the `type()` method, the returned object will be of type `HighLiuk\Eloquent\Model\Post`. By using its own model, this allows you to go further in the possibilities by being able to associate custom methods and properties to it and by returning the result as a `Video` object for example.

Custom content type and meta-data:

```php
// Retrieving 3 elements of a custom content type and retrieving a meta-data (address)
$stores = Post::type('store')->status('publish')->take(3)->get();
foreach ($stores as $store) {
    $storeAddress = $store->address; // option 1
    $storeAddress = $store->meta->address; // option 2
    $storeAddress = $store->fields->address; // option 3
}
```

## Shortcode

Implementation in progress

## Query logs

The connection capsule being directly attached to `wpdb`, all queries can be viewed on debugging tools such as Query Monitor.
