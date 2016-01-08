This is a Symfony bundle based on [nestablejs](https://github.com/BeFiveINFO/Nestable) 

The purpose of this bundle is to demonstrate the creation of a reusable bundle as described in [sharing your bundle](http://practicalsymfony.com/chapter-18-sharing-your-bundle)

The techniques used in creating this bundle simply highlight the possibilities and things to consider when creating a extensible bundle. It does not mean it is the best practice. Note that I used the default crud and doctrine generator to create this bundle. 

The best way to get started is to install the demo bundle. The demo bundle (PageTestBundle) extends NestablePageBundle and has the controllers, entities and formtypes configured. Hack it to your liking!

## Installing the Demo

In composer.json,

```
...
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/bernardpeh/NestablePageBundle"
        }
    ],
...
"require": {
    ...
    "bpeh/nestable-page-bundle": ">0.1.0"
    ...
```

then run

```
composer update
```

in AppKernel.php, init bundle

```
...
new Bpeh\NestablePageBundle\BpehNestablePageBundle(),
new Bpeh\NestablePageBundle\PageTestBundle\PageTestBundle(),
...
```

In config.yml

```
doctrine:
    orm:
        resolve_target_entities:
            Bpeh\NestablePageBundle\Model\PageBase: Bpeh\NestablePageBundle\PageTestBundle\Entity\Page
            Bpeh\NestablePageBundle\Model\PageMetaBase: Bpeh\NestablePageBundle\PageTestBundle\Entity\PageMeta

# Leave default for demo. override this part to use your own entities and formtypes

# bpeh_nestable_page:
#     page_entity: YourBundle\Entity\Page
#     pagemeta_entity: YourBundle\Entity\PageMeta
#     page_type: YourBundle\PageTestBundle\Form\PageType
#     pagemeta_type: YourBundle\PageTestBundle\Form\PageMetaType
```

In routing.yml, add the routes


```
...
my_test_page:
    resource: "@PageTestBundle/Controller/"
    type:     annotation
    prefix:   /pagetest
...
```

To test if everything is working, go to

```
http://yoururl/pagetest
```

## Functional Testing on Demo Bundle

In symfony root installation

```
phpunit -c app bpeh/nestable-page-bundle/PageTestBundle
```

