# PBCms
A PHP based CMS with only the basic functions a CMS needs.

## Navigation
- [Installation](#Installation)
- [Usage](#Usage)
- [Modules](#Modules)

## Installation

1. Start by [downloading](https://github.com/kearfy/pbcms/archive/master.zip) or git clone the _src_ folder into
 your destination directory with the following command: ```git clone   https://github.com/kearfy/pbcms```

  **The next step is only required if PBCMS is not placed in the root directory**

2. Navigate to the PBCMS directory, then into ```app\internal\db\sys\config.json```, inside of _config.json_,
 change ```details\root``` from ```/``` to the sub directory where PBCMS is located.
3. Fire up your webserver and wait for the setup to initialize, now follow the steps in the web-interface
 and you will have a stunning and fast website for your visitors!


## Usage

* _App class_
   1. _file() Function_

      __Note: If a folder has been given in, only__ *exists*, *make* __and__ *delete* __will work...__

      __WARNING: Don't try to delete folders inside of the app folder it self, this will be fixed soon,
        subdirectories will work fine...__
      - __Make__ a File:
        _Returns true or false_
        ```php
          $app->file('make', 'filename.txt');
        ```
      - __Set content__ for a File:
        _Returns true or false_
        ```php
          $app->file('setcontent', 'filename.txt', 'file content');
        ```
      - __Get content__ to a File:
        _Returns the content of a file or false_
        ```php
          $app->file('content', 'filename.txt');
        ```
      - __Empty__ a File:
        _Returns true or false_
        ```php
          $app->file('empty', 'filename.txt');
        ```
      - __Delete__ a File:
        _Returns true of false_
        ```php
          $app->file('delete', 'filename.txt');
        ```
      - Check if a file __Exists__:
        _Returns true or false_
        ```php
          $app->file('exists', 'filename.txt');
        ```
      - __Print__ a File:
        _Returns true or false_
        ```php
          $app->file('print', 'filename.txt');
        ```
      - __Require__ a File:
        _Returns true or false_
        ```php
          $app->file('require', 'filename.php');
        ```

   2. _json() Function__
      - __Encode__ an Array:
        _Returns json or false_
        ```php
          $app->json('encode', $array);
        ```
      - __Decode__ a Json string:
        _Returns an array or false_
        ```php
          $app->json('decode', $json);
        ```
      - __Print__ a Json string:
        _Prints a json string and sets a json header_
        ```php
          $app->json('print', $json);
        ```

   3. _setheader() Function__

      __Note: We use Json as an example, for the registered headers, you can use one from the list
        below, else, you can enter in any header supported by php.__
      __Registered__ header:
        _Sets a header_
        ```php
          $app->setheader('json');
        ```
      __Custom__ header:
        _Sets a header_
        ```php
          $app->setheader('Content-Type: application/json');
        ```

   4. _jdb() Function_

      __Note: This function basicly stores arrays in json file, if you want to store important data,
        consider using a database like mysql!__
      - __New__ Database:
        _Returns true or false_
        ```php
          $app->jdb('new', 'path/of/db');
        ```
      - __Open__ a Database:
        _Returns array or false_
        ```php
          $app->jdb('open', 'path/of/db');
        ```
      - __Update__ a Database:
        _Returns true or false_
        ```php
          $app->jdb('update', 'path/of/db', $newarray);
        ```
      - __Empty__ a Database:
        _Returns true or false_
        ```php
          $app->jdb('empty', 'path/of/db');
        ```
      - __Delete__ a Database:
        _Returns true or false_
        ```php
          $app->jdb('delete', 'path/of/db');
        ```
      - Check if a Database __Exists__:
        _Returns true or false_
        ```php
          $app->jdb('exists', 'path/of/db');
        ```
   5. _module() Function_

      __Note: We use the system plugin: Router. you can use any other plugin__
      - Check if module __Exists__:
        _Returns true or false_
        ```php
          //Check if exists in file system
          $app->module('exists', '_/router/', 'd');

          //Check if registered by name
          $app->module('exists', 'router', 'n');

          //Check if registered by path
          $app->module('exists', '_/router/', 'p');
        ```
      - Obtain module __Info__:
        _Returns module data or false_
        ```php
          $app->module('info', 'router');
        ```
      - __Load__ one or all modules:
        _Returns true or false_
        ```php
          //Load all Modules
          $app->module('load', false);

          //Load a specific Module
          $app->module('load', 'router');
        ```
      - __Register__ a new Module:
        _Returns true or false_

        __Note: When installing a module, the zip file must be unpacked and then
          module must be placed in the directory given in info.json, the module
          must be made correctly, read all about in in__ [Modules](#Modules)
        ```php
          $app->module('register', '_/router/');
        ```

## Modules

* _requirements for creating your own module for PBCMS_
   1. _info.json_:
      This file must contain the following data:
      ```json
        {
          "name" : "My first plugin",
          "dir" : "path/of/module/",
          "class" : "ModuleClass",
          "author" : "John Doe",
          "desc" : "A module made by John Doe"
        }
      ```
    2. _main.php_:

        This file must contain the class specified in the _info.json_ file.

        This file will be included into the script, then you can call the
        class to start executing your script.

        If additional installation steps are required such as creating a
        JDB, the make a public function called _setup()_
    3. _Pack your module in a zip file if you want to share it with other developers._


## Contributing
  _Credits_\n
    __Micha de Vries__\n
    [Instagram](https://www.instagram.com/kearfy/)\n
    [Facebook](https://www.facebook.com/micha.devries.5)\n
    [Twitter](https://twitter.com/Kearfy)\n
    Discord: @kearfy#2835\n

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.
