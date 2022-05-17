window.onload = () => {
    console.log(document.querySelector("#bookingTable > tbody > tr:nth-child(1) ").innerHTML)
}

/*

function getUniqueValuesFromColumns() {

    var unique_col_values_dict = {}

    allFilters = document.querySelectorAll(".table-filter")
    allFilters.forEach(filter_i =>{
        col_index = filter_i.parentelement.getAttribute("col-index");

        const rows = document.querySelectorAll("#bookingTable > tbody > tr")

        rows.forEach((row) => {
            console.log(row.querySelector("td:nth-child("+col_index+")").innerHTML);


            if (col_index in unique_col_values_dict) {

                if (unique_col_values_dict[col_index].includes(cell_value)) {

                } else {
                    unique_col_values_dict[col_index].push(cell_value)

                }

            } else {
                unique_col_values_dict[col_index] = new Array(cell_value)
            }
        })

        for(i in unique_col_values_dict) {

        }
    })
};

function updateSelectOptions(unique_col_values_dict) {
    allFilters = document.querySelectorAll(".table-filter")

    allFilters.forEach((filter_i) => {
        col_index = filter_i.parentElement.getAttribute('col-index')

        unique_col_values_dict[col_index].forEach((filter_i) => {
            filter_i.innerHTML = filter_i.innerHTML + '\n<option value="$[i]">$[i]</option>'
        });
    });
};

function filter_rows() {
    allFilters = document.querySelectorAll(".table-filter")
    var filter_value_dict = {}

    allFilters.forEach((filter_i) => {
        col_index = filter_i.parentElement.getAttribute('col-index')

        value = filter_i.value
        if (value  != "all") {
            filter_value_dict[col_index] = value;
        }
    });

    for (var col_i in filter_value_dict) {
        filter_value = filter_value_dict[col_i]
        row_cell_value = col_cell_value_dict[col_i]

        if (row_cell_value.indexOf(filter_value) == -1 && filter_value != "all") {
            display_row = false;
            break;
        }
    }

    if (display_row == true) {
        row.style.display = "none"
    }
}
*/

/*var bookingArray = [
    {'booking' : 'booking1', 'customer' : 'person1'},
    {'booking' : 'booking2', 'customer' : 'person2'},
]*/

const searchInput = document.getElementById('search');
const rows = document.querySelectorAll(' tbody tr');
//console.log(rows);

searchInput.addEventListener('keyup', function(event) {
   // console.log(event);
   const q = event.target.value;
   rows.forEach(row =>  {
    row.querySelector('td').textContent.toLowerCase().startsWith(q) 
    ? (row.getElementsByClassName.display = "table-row")
    : row.getElementsByClassName.display = 'none'
   });
});