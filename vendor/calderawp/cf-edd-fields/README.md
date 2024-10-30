# cf-edd-fields
These are shared fields for Caldera Forms EDD add-on and Caldera Forms EDD Pro add-on.

**This is not a plugin**
This is used to make two plugins:
* [Caldera Forms EDD](https://calderaforms.com/downloads/edd-for-caldera-forms/)
* [Caldera Forms EDD Pro](https://calderaforms.com/downloads/easy-digital-downloads-for-caldera-forms-pro)

## Fields: 
* [Easy Digital Downloads Software Licencing](https://easydigitaldownloads.com/downloads/software-licensing/) 
 Allows any select field to be a selector for all downloads with an active license of a user (by default the current user.)

```
    add_action( 'plugins_loaded', function(){
        include_once __DIR__ . '/vendor/autoload.php';
        \calderawp\cfeddfields\setup::add_hooks();
    });

```

* Easy Digital Downloads Auto-Population
Adds an auto-population type to select fields with all downloads and a filter.

```
    ( new \calderawp\cfeddfields\fields\populate\query() )->add_hooks();
    add_action( 'caldera_forms_admin_init', function(){
        new \calderawp\cfeddfields\fields\populate\admin();
    });
```
