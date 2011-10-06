<?php

/**
 * Step 1: Require the Slim PHP 5 Framework
 *
 * If using the default file layout, the `Slim/` directory
 * will already be on your include path. If you move the `Slim/`
 * directory elsewhere, ensure that it is added to your include path
 * or update this file path as needed.
 */
require 'Slim/Slim.php';

/**
 * Step 1.5: Load Other Classes & Configure
 */
require 'Views/TwigView.php'; // For Twig Templates
require 'Db/rb.php'; // For RedBean ORM

// Load Beans
require 'Db/post.php';

// Configure Blog
require 'config.php';

class User {
    public static function is_logged_in() {
        if(isset($_SESSION['logged_in'])){
            return true;
        }
        return false;
    }
}

class Data {
    public static $data = array();
}

function authenticate($app, $username, $password) {
    if($username==$app->config('username') && $password==$app->config('password')){
        return true;
    } else {
        return false;
    }
}

$admin_logged_in = function ($app) {
    return function() use ($app) {
        if(! User::is_logged_in()){
            $app->redirect('/');
        }   
    };
};

/**
 * Step 2: Instantiate the Slim application
 *
 * Here we instantiate the Slim application with its default settings.
 * However, we could also pass a key-value array of settings.
 * Refer to the online documentation for available settings.
 */
$app = new Slim(array(
    'view' => new TwigView()
));

$app->config($blog_options);

// Authenticate Function


/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, and `Slim::delete`
 * is an anonymous function. If you are using PHP < 5.3, the
 * second argument should be any variable that returns `true` for
 * `is_callable()`. An example GET route for PHP < 5.3 is:
 *
 * $app = new Slim();
 * $app->get('/hello/:name', 'myFunction');
 * function myFunction($name) { echo "Hello, $name"; }
 *
 * The routes below work with PHP >= 5.3.
 */

// Some global template variables
Data::$data['title'] = $app->config('title');
Data::$data['logged_in'] = (User::is_logged_in()) ? true : false;
Data::$data['subtitle'] = $app->config('subtitle');
Data::$data['next_page'] = 0;


// Blog index
$app->get('/', function () use ($app) { 
    Data::$data['posts_all'] = R::find('post','1 ORDER BY id DESC ');
    $count = count(Data::$data['posts_all']);
    if($count) {
        Data::$data['posts'] = array(Data::$data['posts_all'][key(Data::$data['posts_all'])]);
    }

    
    if($count > 1) {
        Data::$data['next_page'] = 1;
    }

    $app->render('index.php', Data::$data);
});

// Blog Admin Login
$app->get('/login', function () use ($app) {
    $data['title'] = $app->config('title');
    $app->render('login.php', $data);
});

// Blog Admin Post
$app->post('/login', function() use ($app) {
    $username = $app->request()->post('username');
    $password = $app->request()->post('password');

    if(authenticate($app, $username,$password)) {
        $_SESSION['logged_in'] = true;
        $app->redirect('/');
    }

    $app->redirect('/login');
});

// Create new blog post
$app->post('/new_post',  function() use ($app) {
    $title = $app->request()->post('title');
    $body  = $app->request()->post('body');

    if($title && $body) {
        if(new Post($title,$body)) {
            $app->redirect('/');
        } else {
            echo 'Error saving new blog post';
        }
    }
    $app->redirect('/');
});

// Delete a post
$app->get('/delete/:id',$admin_logged_in($app), function($id) use($app){
    $post = R::load('post',$id);
    R::trash($post);
    $app->redirect('/');
});

// Logout page
$app->get('/logout', function() use ($app) {
    session_destroy();
    $app->redirect('/');
});

// Edit a post
$app->get('/edit/:id', $admin_logged_in($app), function($id) use ($app) {
    Data::$data['post'] = R::load('post',$id);
    $app->render('edit.php', Data::$data);
});

// Update a post
$app->post('/edit_post/:id',$admin_logged_in($app), function($id) use($app){
    $title = $app->request()->post('title');
    $body  = $app->request()->post('body');

    if(Post::update($id, $title, $body)) {
        $app->redirect('/');
    } else {
        $app->redirect('/edit/'.$id);
    }
});



// Blog post page
$app->get('/:page', function ($page) use ($app) {
    Data::$data['posts_all'] = R::find('post','1 ORDER BY id DESC ');
    Data::$data['post'] = R::findOne('post','1 ORDER BY id DESC LIMIT '.$page.',1');

    $count = count(Data::$data['posts_all']);
    Data::$data['next_page'] = $page;

    if($count > ($page+1)) {
        Data::$data['next_page'] += 1;
    } else {
        Data::$data['next_page'] = 0;
    }

    $app->render('post.php', Data::$data);
});



/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This is responsible for executing
 * the Slim application using the settings and routes defined above.
 */
$app->run();