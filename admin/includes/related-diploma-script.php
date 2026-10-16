<script type="text/javascript">
    (function () {
        var rowsContainer = document.getElementById('related-diploma-rows');
        var addRowButton = document.getElementById('add-related-diploma-row');

        if (!rowsContainer || !addRowButton) {
            return;
        }

        var debounceTimer = null;
        var selectedIds = {};
        var excludeDiplomaId = <?php echo isset($exclude_diploma_id) ? (int) $exclude_diploma_id : 0; ?>;

        function getSelectedIds(excludeRow) {
            var ids = {};
            var rows = rowsContainer.querySelectorAll('.related-diploma-row');

            for (var i = 0; i < rows.length; i++) {
                if (excludeRow && rows[i] === excludeRow) {
                    continue;
                }

                var hiddenInput = rows[i].querySelector('.related-diploma-id');
                if (hiddenInput && hiddenInput.value) {
                    ids[hiddenInput.value] = true;
                }
            }

            return ids;
        }

        function hideSuggestions(list) {
            list.innerHTML = '';
            list.style.display = 'none';
        }

        function toggleClearButton(row) {
            var clearButton = row.querySelector('.related-diploma-clear');
            var hiddenInput = row.querySelector('.related-diploma-id');

            if (!clearButton) {
                return;
            }

            clearButton.style.display = hiddenInput.value ? 'inline-block' : 'none';
        }

        function clearSelection(row) {
            var searchInput = row.querySelector('.related-diploma-search');
            var hiddenInput = row.querySelector('.related-diploma-id');
            var suggestions = row.querySelector('.related-diploma-suggestions');

            if (hiddenInput.value) {
                delete selectedIds[hiddenInput.value];
            }

            searchInput.value = '';
            hiddenInput.value = '';
            searchInput.readOnly = false;
            hideSuggestions(suggestions);
            toggleClearButton(row);
        }

        function selectSuggestion(row, item) {
            var searchInput = row.querySelector('.related-diploma-search');
            var hiddenInput = row.querySelector('.related-diploma-id');
            var suggestions = row.querySelector('.related-diploma-suggestions');
            var currentId = hiddenInput.value;
            var isPrimaryRow = row.getAttribute('data-row-type') === 'primary';

            if (currentId && currentId !== String(item.id)) {
                delete selectedIds[currentId];
            }

            searchInput.value = item.name;
            hiddenInput.value = item.id;
            searchInput.readOnly = !isPrimaryRow;
            selectedIds[item.id] = true;
            hideSuggestions(suggestions);
            toggleClearButton(row);
        }

        function renderSuggestions(row, items) {
            var suggestions = row.querySelector('.related-diploma-suggestions');
            var takenIds = getSelectedIds(row);

            suggestions.innerHTML = '';

            for (var i = 0; i < items.length; i++) {
                if (takenIds[String(items[i].id)]) {
                    continue;
                }

                var listItem = document.createElement('li');
                listItem.textContent = items[i].name;
                listItem.setAttribute('data-id', items[i].id);
                listItem.setAttribute('data-name', items[i].name);
                listItem.addEventListener('mousedown', function (event) {
                    event.preventDefault();
                    selectSuggestion(row, {
                        id: this.getAttribute('data-id'),
                        name: this.getAttribute('data-name')
                    });
                });
                suggestions.appendChild(listItem);
            }

            suggestions.style.display = suggestions.children.length ? 'block' : 'none';
        }

        function fetchSuggestions(row, query) {
            if (!query) {
                hideSuggestions(row.querySelector('.related-diploma-suggestions'));
                return;
            }

            var url = 'ajax/diploma_suggest.php?q=' + encodeURIComponent(query);

            if (excludeDiplomaId > 0) {
                url += '&exclude_id=' + excludeDiplomaId;
            }

            fetch(url)
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    renderSuggestions(row, data || []);
                })
                .catch(function () {
                    hideSuggestions(row.querySelector('.related-diploma-suggestions'));
                });
        }

        function bindRow(row) {
            var searchInput = row.querySelector('.related-diploma-search');
            var hiddenInput = row.querySelector('.related-diploma-id');
            var suggestions = row.querySelector('.related-diploma-suggestions');
            var clearButton = row.querySelector('.related-diploma-clear');
            var removeButton = row.querySelector('.related-diploma-remove-row');

            searchInput.addEventListener('input', function () {
                if (hiddenInput.value) {
                    delete selectedIds[hiddenInput.value];
                    hiddenInput.value = '';
                    searchInput.readOnly = false;
                    toggleClearButton(row);
                } else if (row.getAttribute('data-row-type') !== 'primary') {
                    searchInput.readOnly = false;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () {
                    fetchSuggestions(row, searchInput.value.trim());
                }, 300);
            });

            searchInput.addEventListener('blur', function () {
                setTimeout(function () {
                    hideSuggestions(suggestions);
                }, 150);
            });

            if (clearButton) {
                clearButton.addEventListener('click', function () {
                    clearSelection(row);
                });
            }

            if (removeButton) {
                removeButton.addEventListener('click', function () {
                    if (hiddenInput.value) {
                        delete selectedIds[hiddenInput.value];
                    }
                    row.parentNode.removeChild(row);
                });
            }
        }

        function createAdditionalRow() {
            var row = document.createElement('div');
            row.className = 'related-diploma-row';
            row.setAttribute('data-row-type', 'additional');
            row.innerHTML = ''
                + '<div class="related-diploma-input-wrap">'
                + '<button type="button" class="related-diploma-clear" style="display:none;" title="Clear selection">&times;</button>'
                + '<input type="text" class="related-diploma-search productfieldlarge" autocomplete="off" placeholder="Start typing diploma name..." />'
                + '<input type="hidden" name="related_diploma_ids[]" class="related-diploma-id" value="" />'
                + '<ul class="related-diploma-suggestions"></ul>'
                + '</div>'
                + '<button type="button" class="related-diploma-remove-row" title="Remove course">&times;</button>';

            rowsContainer.appendChild(row);
            bindRow(row);
        }

        var existingRows = rowsContainer.querySelectorAll('.related-diploma-row');

        for (var i = 0; i < existingRows.length; i++) {
            bindRow(existingRows[i]);

            var existingHiddenInput = existingRows[i].querySelector('.related-diploma-id');
            if (existingHiddenInput && existingHiddenInput.value) {
                selectedIds[existingHiddenInput.value] = true;
                toggleClearButton(existingRows[i]);
            }
        }

        addRowButton.addEventListener('click', createAdditionalRow);
    })();
</script>
