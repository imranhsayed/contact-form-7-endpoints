# CONTACT FORM 7 ENDPOINT

> This plugin provides you different endpoints for Contact Form 7 enquiry form using WordPress REST API

* :bust_in_silhouette: Enquiry Form Endpoint

## Demo:
![](demo.gif)

## Register a new user endpoint:

When we access the end point on URI: `http://example.com/wp-json/cf7e/v1/enquiry`,
and we pass our 'name', 'email', 'subject', 'message' both optionally in query params of the request., we get the following with a status code:
* 200: Email is sent.
* 400: Error when fields are empty or if the user with the given id does not have capability to register user
* 404: Any other error.

## Getting Started :clipboard:

These instructions will get you a copy of the project up and running on your local machine for development purposes.

## Prerequisites :door:

You need to have any WordPress theme activated on your WordPress project, which has REST API enabled.

## Installation :wrench:

1. Clone the plugin directory in the `/wp-content/plugins/` directory, or install a zipped directory of this plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

## Use :ski:
endpoint : `http://example.com/wp-json/cf7e/v1/enquiry`

> Params to be sent in the body
`name(String)`, `email(String)`, `subject(String)` and `message(String)`
Return Value: `User Object or Error (Object)`

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
