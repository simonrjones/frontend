# Setting up your content schema

## How content works

The basic principle of the Frontend project is to read in content from external sources and make it simple to build a 
website out of this.

To do this, we first read in content from a CMS, for example read in content from the WordPress REST API. This raw data 
is converted this into a more useful content object that is passed to the template (Twig).

However, the first step is to tell the application what our content model is so the system can automatically convert raw
 data into something more useful.

Also see:
* For more information on the content model see [Content model](../content-model.md)
* You can find out how to output content fields on the [Templating content fields](../templating/content-fields.md) page 

## Setting up the content schema

This can be done in PHP (via the `Schema` class), but it's more convenient to use YAML files for this. 

### content-schema.yaml

You start with one YAML file with two root elements:

#### content_types

An array of content types, with three properties per content type:

* `content type name` - it is recommended to use plurals for content types (e.g. news, case_studies)
    * `api_endpoint` - The API endpoint URL when accessing data
    * `content_fields` - The YAML file to load for custom fields for this content type 
    * `source_content_type` - The content type in the data source (e.g. in WordPress CMS this is the post_type)  
E.g. 

```yaml
content_types:
  news:
    api_endpoint: wp-json/v2/posts
    content_fields: news.yaml
    source_content_type: posts
  projects:
    api_endpoint: wp-json/v2/projects
    content_fields: projects.yaml
    source_content_type: projects
```

#### global

Global options which are set by default for all your content types. For example, default image sizes.

An array of keys and values, which can be accessed via `$contentType->getOption('my_name')`

```yaml
global:
  my_name: myValue
  image_thumbnails:
    - thumbnail
    - medium
    - medium_large
    - large
```

### content-type-name.yaml 

Each content type then has its own YAML file to define the content fields for that content type.

#### Basic structure 

Each root element is a content type, which has a list of sub-properties.

```yaml
field_name:
  type: field type
  other_options: value
```

The `field_name` should match the field name that comes out of the API. For example, in WordPress ACF content fields 
are stored in the `'acf'` array. So a content field called `hero_image` is expected to be stored in `['acf']['hero_image']` 

The only required property is `type` which must denote the content field type. Any other properties are set 
as options. 

#### Supported content field types

* `array` - an array (list) of multiple records of one type
* `audio` - 
* `boolean` - true or false  
* `date` - date
* `datetime` - date and time
* `decimal` - decimal number
* `document` - 
* `flexible` - flexible content
* `image` - image, supports multiple image sizes and content such as alt text and captions
* `number` - integer number
* `plainarray` - array with simple values (strings, numbers, boolean values), keys can be integers or strings
* `plaintext` - plain text  
* `relation` - a relation to another content type
* `relation_array` - an array of relations
* `richtext` - rich text, this is expected to contain HTML
* `taxonomyterms` -  
* `text` - short text, cannot contain any line returns
* `video` - 

#### Decimal options

* `precision` - the number of decimal places to round to (default = 2)
* `round` - rounding mode used if the passed number needs to be rounding to the required decimal places (default = up). See https://www.php.net/round
    * `up` - rounds up when number is halfway there (e.g. making 1.555 into 1.56)
    * `down` - rounds down when number is halfway there (e.g. making 1.555 into 1.55)
    * `even` - rounds towards the nearest even value (e.g. making 1.551 into 1.56)
    * `odd` - rounds towards the nearest odd value (e.g. making 1.561 into 1.55)

```yaml
length:
  type: decimal
  precision: 4
  round: down
```

#### Image options

Accepts the option `image_sizes` which is an array of available image sizes. E.g.

```yaml
hero_image:
  type: image
  image_sizes:
    - medium
    - large
```

#### Relation options

Accepts the option `content_type` which is the type of content this field points to. This must match up with 
the content type name used in `content-model.yaml`. This can be defined as one content type:

```yaml
team_member:
  type: relation
  content_type: person
```

Or multiple content types:

```yaml
related_posts:
  type: relation
  content_type: 
    - news
    - person
```

When defining multiple content types for a relation you must also define the `source_content_type` which will match the 
`post_type` (in WordPress) with the content type in the returned data.

#### Relation Array options

Accepts the option `content_type` which is the type of content this field points to. This must match up with 
the content type name used in `content-model.yaml`. E.g.

```yaml
team:
  type: relation_array
  content_type: person
```

#### Array options

Accepts the option `content_fields` which contains multiple child content fields.

```yaml
careers:
  type: array
  content_fields:
    #content_fields
```          
 
#### Flexible content options

Accepts the option `components` which contains multiple child components. 

```yaml
page_content:
  type: flexible
  components:
    quote_block:
      # content_fields
    content_block:
      # content_fields
```             

Each child component can then have its own list of content fields. E.g.

```yaml
page_content:
  type: flexible
  components:
    quote_block:
      author:
        type: relation
        content_type: user
      hero_image:
        type: image
      quote_line:
        type: richtext  
```

#### Full example

```yaml
theme:
  type: plaintext
description:
  type: richtext
exclude_from_search:
  type: boolean
hero_image:
  type: image
  image_sizes:
    - thumbnail
    - medium
    - medium_large
    - large
page_content:
  type: flexible
  components:
    content_block:
      content_title:
        type: plaintext
      full_width:
        type: boolean
    quote_block:
      author:
        type: relation
        content_type: user
```
