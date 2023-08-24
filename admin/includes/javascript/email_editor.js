
function debounce (callback, timeout) {
    let timeoutId;

    function handler () {
        timeoutId && window.clearTimeout(timeoutId);

        timeoutId = window.setTimeout( () => {
            // Timeout has expired without being cancelled, call the callback.
            callback();
        }, timeout);
    }

    return handler;
}

jQuery( () => {
    // On initial page load there is no selected template or editors, only a dropdown.
    const htmlTextarea = jQuery('textarea[name=file_contents_html]');
    const textTextarea = jQuery('textarea[name=file_contents_text]');

    // const htmlPreview = jQuery('#preview_html');
    // const formatMap = {
    //     'html': {
    //         'editor': htmlEditor,
    //         'preview': htmlPreview
    //     }
    // };

    function getEditorContents () {
        if (window.hasOwnProperty('CKEDITOR') && CKEDITOR.editors.hasOwnProperty('file_contents_html')) {
            return {
                html: CKEDITOR.editors.file_contents_html.value,
                text: CKEDITOR.editors.file_contents_text.value,
            };
        } else {
            return {
                html: htmlTextarea[0].value,
                text: textTextarea[0].value
            };
        }
    }

    const previewHTMLIframe = document.getElementById('preview_html');
    const previewTEXTIframe = document.getElementById('preview_text');

    /**
     * On page init, load the default template data for the selected email module.
     * This can then be edited before it is used to generate a preview.
     */
    function loadDefaultTemplateData () {
        const name = jQuery('select[name=edit_name]')[0].value;
        zcJS.ajax({
            url: 'ajax.php?act=ajaxEmailEditor&method=getDefaultTemplateData&module=' + name,
            data: { module: name }
        }).then( (response) => {
            console.log('Response: ', response);

            buildTemplateDataInputs(response);

            // Now we have template data we can generate an initial preview.
            showPreview();
        })
    }

    function buildTemplateDataInputs (templateData) {
        const parent = jQuery('#templateDataNode');
        Object.keys(templateData).sort().forEach( (key, idx) => {
            const value = templateData[key];
            // document.createElement
            let span = jQuery('<span>').text(key)[0];
            parent.append(span);
            span.setAttribute('data-key', key);
            span.setAttribute('data-idx', idx);
            let i = jQuery('<input>')[0];
            // If value is an object, serialise it.  Cannot display nested data in a simple text input.
            // See showPreview later.
            if (typeof value == 'object') {
                i.value = JSON.stringify(value);
            } else {
                i.value = value;
            }
            i.setAttribute('data-idx', idx);
            parent.append(i);
        })
    }

    function showPreview (format) {
        // const editor = formatMap[format].editor;
        // const preview = formatMap[format].preview;
        const name = jQuery('select[name=edit_name]')[0].value;
        const contents = getEditorContents();

        // Gather templateData from the inputs on screen
        const parent = jQuery('#templateDataNode');
        let templateData = {};
        let nodes = parent.find('span[data-idx]');
        nodes.each( (idx, node) => {
            const valueNode = parent.find('input[data-idx=' + node.getAttribute('data-idx') + ']')[0];
            const value = valueNode.value;
            try {
                const looksLikeJSON = value.substring(0, 1) == '{' || value.substring(0, 1) == '[';
                templateData[node.getAttribute('data-key')] = looksLikeJSON ? JSON.parse(value) : value;
            } catch (ex) {
                /* Squash JSON errors */
            }
        })

        previewHTMLIframe.contentDocument.body.innerHTML = '<h2>Loading...<h2>';
        previewTEXTIframe.contentDocument.body.innerHTML = '<h2>Loading...<h2>';
        // Language strings will be loaded according to whether you call the catalog or admin side.
        const adminTemplates = [ 'newsletter' ];
        const isAdmin = adminTemplates.indexOf(name) !== -1;
        const path = isAdmin ? '' : '/';
        zcJS.ajax({
            url: `${path}ajax.php?act=ajaxEmailEditor&method=generatePreview`,
            data: JSON.stringify({
                name: name,
                template_html: contents.html,
                template_text: contents.text,
                template_data: templateData
            })
        }).done( renderResults ).catch( (response) => {
            console.error(`email_editor: caught error `);
            response.then( (data) => {

                alert('Error: ' + data);
            })
        })
    }
    function htmlEscape(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\//g, '&#x2F;')
            .replace(/=/g,  '&#x3D;')
            .replace(/`/g, '&#x60;');
    }

    function renderResults (response) {
        console.log(`email_editor: got response`, response);
        if (!response) {
            return;
        }
        // Gracefully handle the backend telling us about a syntax error in the templates
        if (response.view_error) {
            jQuery('#syntax_error_display').show();
            const errNode = document.getElementById('syntax_error_message');
            errNode.innerHTML = response.view_error;

            if (response.lines) {
                response.lines.forEach( (line) => {
                    errNode.innerHTML += "\n" + htmlEscape(line)
                })
            }

            previewHTMLIframe.contentDocument.body.innerHTML = 'Syntax Error :(';
            previewTEXTIframe.contentDocument.body.innerHTML = 'Syntax Error :(';
            return;
        }
        jQuery('#syntax_error_display').hide();
        // Gracefully handle any other severe error
        if (response.error) {
            alert(response.error);
            return;
        }

        previewHTMLIframe.contentDocument.body.innerHTML = response.html;
        previewTEXTIframe.contentDocument.body.innerHTML = '<pre>' + response.text + '</pre>';
    }

    /**
     * Handle the user toggling the auto-regen checkbox on or off.
     *
     * @param Event e
     */
    let regenHandles = [];
    function updateRegen (e) {
        const enabled = e.target.checked;
        const debouncedRefresh = debounce(showPreview, 1000);

        if (enabled) {
            regenHandles.push(htmlTextarea.on('change', debouncedRefresh));
            regenHandles.push(htmlTextarea.on('keyup', debouncedRefresh));
            regenHandles.push(textTextarea.on('change', debouncedRefresh));
            regenHandles.push(textTextarea.on('keyup', debouncedRefresh));
        } else {
            // if (regenHandles.length !== 0) {
                // regenHandles.forEach( (h) => h.cancel);
                // regenHandles.length = 0;
            // }
            htmlTextarea.off();//'change', debouncedRefresh);
            textTextarea.off();//'keyup', debouncedRefresh);
        }
    }

    const saveFeedbackNode = document.getElementById('saveFeedbackNode');
    function showSaveFeedback (msg) {
        if (!msg) {
            jQuery(saveFeedbackNode).hide();
        } else {
            jQuery(saveFeedbackNode).text(msg);
            jQuery(saveFeedbackNode).show();
        }
    }

    function saveTemplates (e) {
        showSaveFeedback('Saving...');
        const contents = getEditorContents();
        const name = jQuery('select[name=edit_name]')[0].value;
        zcJS.ajax({
            url: `ajax.php?act=ajaxEmailEditor&method=saveTemplates&module=${name}`,
            data: JSON.stringify({
                name: name,
                template_html: contents.html,
                template_text: contents.text
            })
        }).done( (data) => {
            showSaveFeedback('Saved OK');
            renderResults();
        }).catch( (response) => {
            showSaveFeedback('Error saving templates!');
        });
    }

    function tieHeight (a, b) {
        a = jQuery(a);
        a.resize( (e) => {
            console.log('match buddy to height: ' + a.css('height'));
            jQuery(b).css('height', a.css('height'))
        })
    }

    /**
     * Page startup.
     * Set the editors into 'source' mode by default, rather than WYSIWYG.
     * startupMode: 'source'
     */
    window.setTimeout( () => {
        if (htmlTextarea) {
            // Page has an email template selected for editing.  May be in rich text mode
            // But rich text mode does not play well with the embedded Blade operators.
            if (window.hasOwnProperty('CKEDITOR') && CKEDITOR.editors && CKEDITOR.editors.hasOwnProperty('file_contents_html')) {

                // htmlEditor.setMode('source');
                CKEDITOR.editors.file_contents_html.setMode('source');
            }

            // showPreview();
            loadDefaultTemplateData();

            // Buttons bar
            jQuery('#regen_previews_button').on('click', showPreview);

            jQuery('#auto_regen_cb').on('change', updateRegen);

            jQuery('.btn-primary.save').on('click', saveTemplates);

            // Maintain preview heights
            tieHeight(previewHTMLIframe, previewTEXTIframe);
            tieHeight(previewTEXTIframe, previewHTMLIframe);
        }
    }, 1000)
})