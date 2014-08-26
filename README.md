# CakePHP-Plist
With CakePHP-Plist it’s easy to generate Plists from your CakePHP app. It uses a view class, so you can use it by extension parsing, just like the built-in JSON support. Both plain and binary plists are supported.

## Installation
* Add the plugin to your app.
* Import the plugin in your `bootstrap.php` by adding `CakePlugin::load('Plist', array('bootstrap' => false, 'routes' => true));`. Make sure you enable `routes` or add `Router::parseExtensions('plist’);` to your own route file.
* Replace the existing `$Dispatcher->dispatch()` call in your `webroot/index.php` to the code below. This is necessary because CakePHP doesn’t know how to handle `plist` files.

    $Dispatcher->dispatch(
        new CakeRequest(),
        new CakeResponse(array(
            'type' => array(
                'plist' => 'application/xml',
                'plist-binary' => 'application/x-plist',
            )
        ))
    );

* Load the `RequestHandler` in either the `AppController` or any other controller you would like to use it in. You need to let the `RequestHandler` know about the plugin:

    public $components = array('RequestHandler' => array(
        'viewClassMap' => array(
            'plist' => 'Plist.Plist'
        )
    ));

## Usage
To generate plists from your app, you simple use CakePHP’s serialization method, as shown on this web page: http://book.cakephp.org/2.0/en/views/json-and-xml-views.html.

Simple example to test the installation:

    class PlistTestController extends AppController {
        public $uses = array();
        public function test() {
            $data = array(
                'names' => array(1, 2, 3)
            );
            $_serialize = array_keys($data);
            $this->set(compact('_serialize') + $data);
        }
    }

Open `/plist_test/test.plist` to see if it works. If both JSON (or even XML) and Plist extension parsing is enabled, you can get either of them by changing the extension.

## Binary
To get a binary plist, you can either set a `_binaryPlist` view var to `true` to get a single action rendered as a binary plist or set a `binaryPlist` config value to true. 

If you have a config value set, the view var will overrule it.
