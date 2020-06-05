function floorshowListResults() {
    return {
        init(el) {
            this.el = el;

            window.livewire.hook('afterDomUpdate', () => {
                this.selectNone();
            });
        },
        checkboxes() {
            return this.el.querySelectorAll('[x-ref=selected_ids]');
        },
        selectAllIfNoneChecked() {
            if (!this.anyChecked()) {
                this.selectAll();
            }
        },
        select(e, el) {
            const action = e.target.value;
            const check = action == 1;
            this.boxes(check);

            this.resetSelector(e);
        },
        selectNone() {
            this.boxes(0);
        },
        selectAll() {
            this.boxes(1);
        },
        boxes(check) {
            this.checkboxes().forEach(function (checkbox, index, checkboxes) {
                checkbox.checked = check;
            })
        },
        anyChecked() {
            let anyChecked = false;
            // we cannot break out of forEach, otherwise use
            // for loop
            this.checkboxes().forEach(function (checkbox, index, checkboxes) {
                if (checkbox.checked) {
                    anyChecked = true;
                }
            })

            return anyChecked;
        },
        actOnOption: function (e) {
            this.selectAllIfNoneChecked();

            const target = e.target;
            const form = target.closest('form');

            // if we have data-url present
            // submit the form
            const url = target.selectedOptions[0].dataset.url;
            if (url) {
                form.setAttribute('action', url);
                form.submit();
            }

            // if we have data-call present, find
            // data-call-event in parent element

            const call = target.selectedOptions[0].dataset.call;
            if (call) {
                const callEvent = target.dataset.callEvent;
                console.log(target);
                console.log('callEvent', callEvent);
                if (callEvent) {
                    this.emitWithSelection(callEvent, e)
                }
            }

            console.log('url', url);
            console.log('call', call);

            this.resetSelector(e);
            this.boxes(0);
        },
        emitWithSelection: function (componentEvent, e) {
            // prepare selectedIds
            const selectedEls = document.querySelectorAll('[x-ref=selected_ids]:checked');
            const selectedIds = [];
            selectedEls.forEach(function (currentValue) {
                this.push(currentValue.value);
            }, selectedIds);

            // emit
            console.log(componentEvent, selectedIds, e.target.selectedOptions[0].dataset.call);
            window.livewire.emit(componentEvent, selectedIds, e.target.selectedOptions[0].dataset.call);
        },
        resetSelector(e) {
            e.target.selectedIndex = 0;
        }
    }
}
