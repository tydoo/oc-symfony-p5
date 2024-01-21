import './app.scss';

//DSFR
import '../node_modules/@gouvfr/dsfr/dist/dsfr.min.css';
import '@gouvfr/dsfr/dist/dsfr.module';
import '../node_modules/@gouvfr/dsfr/dist/utility/utility.min.css';
import '../node_modules/@gouvfr/dsfr/dist/dsfr.print.min.css';

import * as Turbo from "@hotwired/turbo"
import Stackedit from './js/StackEdit';

document.addEventListener("turbo:load", function () {
    document.querySelectorAll('.markdown-editor').forEach((editor) => {
        editor.addEventListener('click', (e) => {
            e.target.blur();
            e.preventDefault();
            const stackedit = new Stackedit();

            // Open the iframe
            stackedit.openFile({
                name: 'Filename', // with an optional filename
                content: {
                    text: e.target.value // and the Markdown content.
                }
            });

            // Listen to StackEdit events and apply the changes to the textarea.
            stackedit.on('fileChange', (file) => {
                e.target.value = file.content.text;
            });
        })
    })
})
