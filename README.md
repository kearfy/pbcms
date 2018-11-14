# PBCms
A php based cms with only the basic function a cms needs

## _standard classes and functions_

#### _App class_
Contains bare base function for file managing

###### __File()__

Make a file:
[$app->file('make', 'example.txt');]

Set content of a file:
[$app->file('setcontent', 'example.txt', 'This is the content of this file');]

Add content to a file:
[$app->file('addcontent', 'example.txt', ' and this is even more content');]
