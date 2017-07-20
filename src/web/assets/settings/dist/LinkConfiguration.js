(function($) {
    /** global: Craft */
    /** global: Garnish */
    Craft.LinkTypeManager = Garnish.Base.extend(
    {
        $typeSelect: null,
        $spinner: null,
        $typesContainer: null,
        $types: null,
        $nav: null,
        namespace: null,

        init: function($typeSelect, $typesContainer, namespace) {
            this.$typeSelect = $typeSelect;
            this.$spinner = $('<div class="spinner hidden"/>').insertAfter(this.$typeSelect.parent());
            this.$typesContainer = $typesContainer;
            this.$types = $typesContainer.children('.types');
            this.$nav = $typesContainer.children('.tabs').children('ul');
            this.namespace = namespace;

            // Load existing
            $.each(this.$nav.children(), $.proxy(function(index, value) {
                console.log(index, value);
                
                var $nav = $(value);
                var $link = $nav.children('a');
                var id = $link.attr('href');
                
                var LinkType = new Craft.LinkType(
                    this);
                
                LinkType.setHtml($(id));
                LinkType.$nav = $(value);

                console.log(LinkType)
                
            }, this));

            this.addListener(this.$typeSelect, 'change', 'onTypeChange');
        },
        getCount: function() {
          return this.$nav.children().length
        },
        onTypeChange: function(ev) {
            this.$spinner.removeClass('hidden');

            var val = this.$typeSelect.val();

            if(!val) {
                return;
                
            }
            var data = {
                fieldId: this.fieldId,
                type: this.$typeSelect.val(),
                namespace: this.namespace+'[type-'+(this.getCount()+1)+']'
            };

            Craft.postActionRequest('link/type/settings', data, $.proxy(function(response, textStatus) {
                this.$spinner.addClass('hidden');

                if (textStatus == 'success') {
                    this.appendType(new Craft.LinkType(
                        this,
                        response.label,
                        response.paneHtml
                    ));

                    Craft.appendHeadHtml(response.headHtml);
                    Craft.appendFootHtml(response.footHtml);
                }
            }, this));
        },
        appendType: function(LinkType) {

            // Append new html and nav
            this.$types.append(LinkType.$html);
            this.$nav.append(LinkType.$nav);
            
            this.refresh();

        },
        refresh: function() {

            this.$typeSelect.val(0);

            // Remove existing pane
            var pane = this.$typesContainer.data('pane');

            if(pane) {
                pane.deselectTab();
                pane.destroy();
            }

            if(this.$nav.children().length <= 0) {
                this.$typesContainer.hide();
                return;
            }

            // Init new tabs
            this.$typesContainer.show()
                .pane();

            Craft.initUiElements(this.$typesContainer);

        }

    });

    Craft.LinkType = Garnish.Base.extend(
    {
        manager: null,
        id: null,
        label: null,
        $html: null,
        $nav: null,
        $link: null,
        $remove: null,

        init: function(manager, label, html) {
            
            this.manager = manager;
            this.id = Math.random().toString(36).substr(2, 5);
            this.label = label;
            
            if(html) {
                this.setHtml(
                    $('<div/>', {
                        class: 'type',
                        id: this.id
                    }).html(html)
                );
            }

            if(label) {
                this.$link = $('<a/>', {
                    text: this.label,
                    class: 'tab sel',
                    href: '#'+this.id
                });
                this.$nav = $('<li/>').html(this.$link);
            }
            
        },
        setHtml: function($html) {
            this.$html = $html;
            this.$remove = this.$html.find('.remove');
            if(this.$remove.length) {
                this.addListener(this.$remove, 'click', 'onRemove');
            }
        },
        onRemove: function (e)
        {
            e.preventDefault();
            this.$html.remove();
            this.$nav.remove();
            this.manager.refresh(true);
            this.destroy();
        }
    });
})(jQuery);