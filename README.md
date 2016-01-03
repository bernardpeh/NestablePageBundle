This is a demo Symfony bundle as described in [sharing your bundle](http://practicalsymfony.com/chapter-18-sharing-your-bundle)

## Installation

in AppKernel.php, init bundle

```
 new Bpeh\NestablePageBundle\BpehNestablePageBundle(),
```

The default crud has been implemented. Add the routes to test the app.


```
bpeh_nestable_page:
    resource: "@BpehNestablePageBundle/Controller/"
    type:     annotation
    prefix:   /
```
