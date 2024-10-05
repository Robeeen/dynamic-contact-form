console.log("hi");

document.getElementById('add-new-field').addEventListener('click', function(e){
    e.preventDefault();

    var table = document.getElementById('dynamic-field-list').getElementsByTagName('tbody')[0];
    var rowCount = table.rows.length;
    var row = table.insertRow(rowCount);

    row.innerHTML = `
        <td><input type="text" name="form_fields[${rowCount}][name] /></td>
        <td>
            <select name="form_fields[${rowCount}][type]">
                <option value="text">Text</option>
                <option value="email">Email</option>
                <option value="Number">Phone</option>
            </select>
        </td>
        <td><button class="remove-field">Remove</button></td>   
    `;
});

document.addEventListener('click', function(e){
    e.preventDefault();
    var row = e.target.closest('tr');
    row.remove();
});

