# Notes for Contributors:
**For those of you in the community who wish to contribute in this repository please read the following guidelines**


## 1. Project Description:
### User Interface (UI):
In order to work on the UI of the session history tab you can find all relevant files in the js directory including all the vue components as well as the app.js file

### API:
You can find all the PHP files used to run the server side requests in the src directory.These include
- The end points used for the UI (./Endpoint/..).
- The HTTP requests made to the thawani API (./WC_Gateway_ThawaniGateway.php)

#### Coding Standards:
- Please remaining consistent with indendations and fuction structures
- Ensure your code is well commented
- Ensure your code is clear and understandable
- Use short and descriptive variable and function names

### For PHP 
- please use the [PSR](https://www.php-fig.org/psr/) standard when writing code
- Make sure that PHP classes follows [PSR-4](https://www.php-fig.org/psr/psr-4/) standards. Under `Thawani` namespace.
- Use wordpress helper functions. 
- Any hardcoded text needs to be inside [__()](https://developer.wordpress.org/reference/functions/__/) function. use `thawani` as text domain. This is used for language support. 


### Vuejs 
For front-end development we utilise the Vuejs framework with [laravel-mix](https://laravel-mix.com/) for building developemnt/prodution js bundles.

#### During development 
1. Ensure that nodejs is installed 
2. Install nodejs dependancies by `npm i`
3. Start development HRM by running `npm run hot`
    3.1 This will load the bundle in port 8080 `http://localhost:8080/dist/app.js`
4. open `template/index.php` and add the full link of the running bundle. 

**code Sample** 
```php
// template/index.php
<div id="thawani_url_admin" data-url="<?php echo site_url(); ?>"></div>
<div id="app"></div>
<script src="<?php echo plugins_url('dist/app.js', __DIR__); ?>"></script><div id="thawani_url_admin" data-url="<?php echo site_url(); ?>"></div>
<div id="app"></div>
- <script src="<?php echo plugins_url('dist/app.js', __DIR__); ?>"></script>
+ <script src="<?php echo plugins_url('http://localhost:8080/dist/app.js', __DIR__); ?>"></script>
```



## 2. Issues:
When creating an issue or starting a conversation in the discussions tab:
- Stick to the issues template
- Try to be as descriptive as possible
- Make the issue or idea clear
- Please refreain from discussing or addressing issues irrelavent to the plugin

## 3. Pull Requests
After you finish implementing your feature or fixing a bug:
- Create a Pull request and assign the correct reviewers
- Use the Pull request template and fill out all the relevant information
- Make sure your version of the plugin pass all the unit tests 
- Make sure you include unit tests for the added functionality if necessary. This help your fellow programmers in the future
