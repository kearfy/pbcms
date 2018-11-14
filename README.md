# PBCms
A php based cms with only the basic function a cms needs

## _standard classes and functions_

#### -_App class_
Contains bare base function for file managing

###### -__File()__

1. Make a file:
```$app->file('make', 'example.txt');```

2. Set content of a file:
```$app->file('setcontent', 'example.txt', 'This is the content of this file');```

3. Add content to a file:
```$app->file('addcontent', 'example.txt', ' and this is even more content');```
