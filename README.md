# PBCms
A php based cms with only the basic function a cms needs

## Navigation
- [Installation](#Installation)
- [Usage](#Usage)
- [Modules](#Modules)

## Installation

1. Start by [downloading](https://github.com/kearfy/pbcms/archive/master.zip) or git clone it into
 your destination directory with the following command: ```git clone   https://github.com/OMSP/OMS```

  **the next step is only required if pbcms is not place in the root directory**

2. Navigate to the pbcms directory, then into ```app\internal\db\sys\config.json```, inside of _config.json_,
 change ```details\root``` from ```/``` to the sub directory where pbcms is located.
3. Fire up your webserver and wait for the setup to initialize, now follow the steps in the webinterface
 and you will have a stunning and fast website for your visitors!


## Usage

* _App class_
   1. _File() Function_
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
        _Returns the content of a file_
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

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.
