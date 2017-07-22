---
layout: default
title: Twig
permalink: twig/
---

# Twig

When rending a Link field, you are actually interacting with a number of Link Types which implement a common interface `TypeInterface`.  Therefore, 
each Link Type can provide additional properties, but all will have the following:

By default, the following [Twig][] tags are available:

{% include tier2nav.html url=page.url recursive=false removeFirst=true %}

--- 

{% assign variableUrl = page.url|append:'tags/' %}
### Tags

[Twig][] [Tags][] are the interface when interacting with Rating data.  

{% include tier3nav.html url=variableUrl recursive=false %}

---

{% assign filterUrl = page.url|append:'filters/' %}
### Filters

[Twig][] [Filters][] allow for extra manipulation of the data before it is rendered.

{% include tier3nav.html url=filterUrl recursive=false %}

[Twig]: http://twig.sensiolabs.org/ "Twig is a modern template engine for PHP"
[Tags]: http://twig.sensiolabs.org/doc/tags/index.html "Twig Tags"
[Filters]: http://twig.sensiolabs.org/doc/filters/index.html "Twig Filters"
