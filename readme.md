Eliminate\Session
===================

This package provides two features:
   * you can set session data store format (aka serializers)
        - php-serialized style
        - json style (Now Redis Only.)
   * seperated session key prefix when use cacheBased Session Handler.

> when using json serializer, the session key is generated in mongoId style.

## How to use

  - Step 1
  
  ```
  composer require eliminate/session ~1.0.0
  ```
  
  - Step 2

  Edit your config/app.php

  Replace

        ```
        'Illuminate\Session\SessionServiceProvider',
        ```

  To

        ```
        'Eliminate\Session\SessionServiceProvider',
        ```

  - Step 3

  Add these line to your app/session.php

    ```

        'serializer' => 'json', // or php

        'prefix' => '',

    ```

## Feel free to contribute.


### The MIT License (MIT)

Copyright (c) 2014 typecho-app-store

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
