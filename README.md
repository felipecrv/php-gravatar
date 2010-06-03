    Details about how Gravatar works can be found at
    http://site.gravatar.com/site/implement

AUTHOR
======

Original class by
    
    Lucas Araújo <araujo.lucas [at] gmail.com>

Lucas's original implementation may be found at:
http://www.phpclasses.org/browse/package/4227.html
    
I've done many changes and added some facilities.
    
    Felipe O. Carvalho <felipekde [at] gmail.com>
    
USAGE
=====

See docs/index.html for more information but the basic usage is very simple:

    $gravatar = new Gravatar("user@email.com");
    echo $gravatar; // <img src="http://gravatar.com/...
