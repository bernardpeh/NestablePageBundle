This is a Symfony bundle based on [nestablejs](https://github.com/BeFiveINFO/Nestable) 

The purpose of this bundle is to demonstrate the creation of a reusable bundle as described in [Making your bundle reusable](http://practicalsymfony.com/chapter-18-making-your-bundle-reusable/)

The techniques used in creating this bundle simply highlight the possibilities and things to consider when creating a extensible bundle. It does not mean it is the best practice. Note that I used the default crud and doctrine generator to create this bundle. 

The best way to get started is to install the demo bundle. The demo bundle (PageTestBundle) extends NestablePageBundle and has the controllers, entities and formtypes configured. Hack it to your liking!

## Installation

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
...
```

In config.yml

```
doctrine:
    orm:
        resolve_target_entities:
            Bpeh\NestablePageBundle\Model\PageBase: Bpeh\NestablePageBundle\Entity\Page
            Bpeh\NestablePageBundle\Model\PageMetaBase: Bpeh\NestablePageBundle\Entity\PageMeta

# Leave default. override this part if you are extending the bundle

# bpeh_nestable_page:
#     page_entity: YourBundle\Entity\Page
#     pagemeta_entity: YourBundle\Entity\PageMeta
#     page_form_type: YourBundle\PageTestBundle\Form\PageType
#     pagemeta_form_type: YourBundle\PageTestBundle\Form\PageMetaType
```

In routing.yml, add the routes

```
...
nestable_page:
    resource: "@BpehNestablePageBundle/Controller/"
    type:     annotation
    prefix:   /pagetest
...
```

To test if everything is working, go to

```
http://yoururl/bpeh_page
```

## Functional Testing

In symfony root installation

```
phpunit -c app vendor/bpeh/nestable-page-bundle/PageTestBundle

## to test on single function
phpunit -c app --filter testSingleLocalePerPageMeta vendor/bpeh/nestable-page-bundle/
```

## Extending the Bundle

Refer to the demo PageTestBundle folder.

Remember to configure the resolve_target_entities and bpeh_nestable_page parameters in config.yml

## Contributing

If you have found a bug, feel free to create a pull request. 

## Copyright and License

MIT License
