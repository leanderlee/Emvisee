
    Emvisee (pronounced MVC)
    By Leander Lee
    
      Created: Mar 24, 2011
    
      Contains:
        - controllers
        - templating
        - services
        - useful php classes
        - (basic) testing suite
        - jquery minimized
    
    
    Is a really lightweight mvc framework
    for PHP, similar to many of the existing
    frameworks out there. However, I chose
    to do my own, because it uses autoloading
    static classes, so that the controllers
    do not have any additional markup.
    
    This was created because:
        - There aren't enough php frameworks (lol)
        - I needed something light(er) weight
        - I wanted something that looked nice
          (ie, none of that $, ->, @ crap.)
        - Had automated testing
    
    The router is compact and unit-tested.
    This uses Twig for templating (sensiolabs).
    I also wrote a (basic) testing framework.
    
    
    INSTALLATION
        Just copy it into webroot.
        Edit settings.conf and hide from world.
    
    USAGE
        There are three things:
            <webroot>/
            <webroot>/tests/
            <webroot>/get/
    
    
    controllers/
        contains .php files with a single 
        class called <filename>_controller.
    lib/
        contains .php files with a single
        class called <filename>. This is
        automatically loaded when called.
    static/
        hosts all css, images and js files.
    tml/
        contains .tml files in folders
        associated to the controller file.
        
        More information about the template
        language and syntax can be found at:
        
        http://www.twig-project.org/doc/templates.html
    tests/
        contains .test files which can be run
        by the test suite.
    
    
    