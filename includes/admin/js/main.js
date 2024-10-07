document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('add-new-field').addEventListener('click', function (e) {
        e.preventDefault();
        var table = document.getElementById('dynamic-field-list').getElementsByTagName('tbody')[0];
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        row.innerHTML = `
            <td><input type="text" name="dff_fields[${rowCount}][name]" class="form-control" /></td>
            <td>
                <select name="dff_fields[${rowCount}][type]" class="field-type-selector form-control">
                    <option value="text">Text</option>
                    <option value="email">Email</option>
                    <option value="number">Number</option>
                    <option value="radio">Radio</option>
                    <option value="select">Dropdown</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="date">Date Field</option>
                </select>
            </td>
            <td><input type="text" name="dff_fields[${rowCount}][options]" placeholder="Comma separated options" class="form-control"  /></td>
            <td><button id="remove-field" class="btn btn-danger">Remove</button></td>
        `;
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'remove-field') {
            e.preventDefault();
            var row = e.target.closest('tr');
            row.remove();
        }
    });
});

