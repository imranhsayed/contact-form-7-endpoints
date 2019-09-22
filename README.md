# CONTACT FORM 7 ENDPOINT

> This plugin provides you different endpoints for Contact Form 7 registration using WordPress REST API

* :bust_in_silhouette: Register Endpoint

## Register a new user endpoint:

When we access the end point on URI: `http://your-domain/wp-json/wp/v2/rae/post/create`,
and we pass our 'user_id', 'title' and 'content' in the body using postman, we get the following with a status code:
* New user data is created.
* Error when fields are empty or if the user with the given id does not have capability to register user
* Any other error.

## Getting Started :clipboard:

These instructions will get you a copy of the project up and running on your local machine for development purposes.

## Prerequisites :door:

You need to have any WordPress theme activated on your WordPress project, which has REST API enabled.

## Installation :wrench:

1. Clone the plugin directory in the `/wp-content/plugins/` directory, or install a zipped directory of this plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

## Use :ski:

> Params to be sent in the body
`username(String)` and `password(String)`
Return Value: `User Object or Error (Object)`

* :page_with_curl: Create Post End Point `http://your-domain/wp-json/wp/v2/rae/post/create`

> Params to be sent in the body
`user_id(Int)`, `title(String)` and `content(String)`
Return Value: `Object with post ID and status or Error (Object)`

## Contributing :busts_in_silhouette:

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

I use [Git](https://github.com/) for versioning. 

## Author :pencil:

* **[Imran Sayed](https://twitter.com/imranhsayed)**
* **[Smit Patadiya](https://twitter.com/smit_patadiya)**

## License :page_facing_up:

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)

- **[MIT license](http://opensource.org/licenses/mit-license.php)**
