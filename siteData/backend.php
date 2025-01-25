<?php
	$file = GSDATAOTHERPATH . 'SiteDataSettings.json';
	if (!file_exists($file)) {
		file_put_contents($file, '[]');
	};
	$datas = file_get_contents(GSDATAOTHERPATH . 'SiteDataSettings.json');

	global $SITEURL;
	echo '
	<link rel="stylesheet" href="'.$SITEURL.'plugins/siteData/assets/w3.css">
	<link rel="stylesheet" href="'.$SITEURL.'plugins/siteData/assets/w3-custom.css">
	<script src="'.$SITEURL.'plugins/siteData/assets/alpine.min.js" ></script>
	';
?>

<div x-data='{
    datas: <?php echo $datas; ?>,
    active: "data",
    options: ["input", "textarea"],
    titleElement: "",
    optionElement: "input",
    pushNew() {
        if (this.titleElement === "") {
            alert("Add title label!");
        } else {
            let newObject = {};
            newObject.id = this.titleElement.toLowerCase().replace(/\s+/g, "").replace(/[^\w\s]/gi, "");
            newObject.title = this.titleElement;
            newObject.option = this.optionElement;
            newObject.data = JSON.stringify(newObject.data);
            this.datas.push(newObject);
            this.titleElement = "";
            this.optionElement = "input";
        }
    }
}' x-init="initSortable">

	<header class="w3-border-bottom w3-margin-bottom">
		<h3 class="floated"><svg xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle" width="24" height="24" viewBox="0 0 24 24"><rect width="24" height="24" fill="none"/><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M21.5 16.052V7.948a4.14 4.14 0 0 0-1.236-2.945a4.25 4.25 0 0 0-2.985-1.22H6.72a4.25 4.25 0 0 0-2.985 1.22A4.14 4.14 0 0 0 2.5 7.948v8.104c0 1.105.445 2.164 1.236 2.945a4.25 4.25 0 0 0 2.985 1.22H17.28c1.12 0 2.193-.44 2.985-1.22a4.14 4.14 0 0 0 1.236-2.945"/><path d="M8.552 12.14a2.054 2.054 0 1 0 0-4.108a2.054 2.054 0 0 0 0 4.108m3.081 3.828c0-.812-.324-1.59-.902-2.165a3.09 3.09 0 0 0-4.358 0a3.05 3.05 0 0 0-.902 2.165m9.097-7.049h3.594M14.568 12h1.54m-1.54 3.081h3.594"/></g></svg> Site Data</h3>
		<p class="clear w3-margin-bottom">Add custom easy to update fields in your site templates, like Phone Number, Email Address, Business Hours, etc.</p>
	</header>
						
	<div class="w3-container w3-margin-bottom">

		<div class="w3-row">
			<a href="javascript:void(0)" onclick="openTab(event, 'Data');">
				<div class="w3-half tablink w3-bottombar w3-hover-light-grey w3-padding w3-border-red">Data</div>
			</a>
			<a href="javascript:void(0)" onclick="openTab(event, 'Settings');">
				<div class="w3-half tablink w3-bottombar w3-hover-light-grey w3-padding">Settings</div>
			</a>
		</div>

		<div id="Data" class="w3-container w3-margin-top mytabs">
		
			<ul style="list-style: none; padding: 0; margin: 0;">
				<template x-for="data in datas" :key="data.id+data.option">
					<li style="margin-bottom: 10px; display: flex; align-items: center;">
						<div style="flex: 1;">
							<label class="w3-text-blue"><b x-text="data.title"></b></label>
							<input class="w3-input w3-border w3-light-grey w3-margin-bottom" type="text" x-value="data.data" x-show="data.option=='input'" x-model="data.data">
							<textarea class="w3-input w3-border w3-light-grey w3-margin-bottom" x-show="data.option=='textarea'" x-model="data.data" x-html="data.data.replace('\u0027',`'`)" rows="5"></textarea>
						</div>
					</li>
				</template>
			</ul>
			
		</div>

		<div id="Settings" class="w3-container w3-margin-top mytabs" style="display:none">
		
			<div class="w3-row-padding">
				<div class="w3-third">
					<input class="w3-input w3-border" type="text" pattern="[A-Za-z0-9]+" placeholder="Title" x-model="titleElement">
				</div>
				<div class="w3-third">
					<select class="w3-select w3-padding w3-margin-bottom" x-model="optionElement">
						<option value="choose" disabled>Choose...</option>
						<template x-for="option in options" :key="option">
						<option x-value="option" x-text="option"></option>
					</template>
					</select>
				</div>
				<div class="w3-third w3-center">
					<button @click="pushNew">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><rect width="24" height="24" fill="none"/><path fill="#0076CC" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10s10-4.477 10-10S17.523 2 12 2m5 11h-4v4h-2v-4H7v-2h4V7h2v4h4z"/></svg>
					</button>
				</div>
			</div>  
			
			<hr>
			
			<table class="w3-table w3-striped">
				<tr class="w3-gray">
					<th>Title</th>
					<th>Type</th>
					<th>Placeholder</th>
					<th> </th>
				</tr>
					
			<template x-for="(data, index) in datas" :key="data.title+data.option">
				<tr draggable="true">
					<td><b x-text="data.title"></b></td>
					<td><i x-text="data.option"></i></td>
					<td>
						<code class="w3-codespan w3-tiny" onclick="copyToClipboard(this)">[% siteData=<span x-text="data.id"></span> %]</code>
						<br>
						<code class="w3-codespan w3-tiny" onclick="copyToClipboard(this)">&lt;?php siteData("<span x-text="data.id"></span>");?&gt;</code>
					</td>
					<td>
						<button @click="datas.splice(index, 1);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><rect width="24" height="24" fill="none"/><path fill="#f00" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zm2-4h2V8H9zm4 0h2V8h-2z"/></svg></button>
					</td>
				</tr>
			</template>
			
			</table>
			
		</div>
			
		<form method="post">
			<textarea name="json" x-html="JSON.stringify(datas, null, 4)" style="display:none"></textarea>
			<input type="submit" name="saver" value="Save" class="w3-btn w3-green w3-round w3-margin-bottom">
		</form>

	</div>
		
	<footer class="w3-border-top w3-padding-top-32 margin-bottom-none">
		<p class="w3-small clear w3-margin-bottom"><a href="javascript:;">Auther / PayPal</a><p>
	</footer>
	
	<script>
	// Get the Tabs
	function openTab(evt, tabName) {
		var i, x, tablinks;
		x = document.getElementsByClassName("mytabs");
		for (i = 0; i < x.length; i++) {
			x[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablink");
		for (i = 0; i < x.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" w3-border-red", "");
		}
		document.getElementById(tabName).style.display = "block";
		evt.currentTarget.firstElementChild.className += " w3-border-red";
	}
	
	// Copy to Clipboard
	function copyToClipboard(element) {
		const text = element.textContent; // Get the text content of the clicked element
		const el = document.createElement('textarea'); // Create a textarea element
		el.value = text; // Set its value to the text content
		document.body.appendChild(el); // Append it to the body
		el.select(); // Select its content
		document.execCommand('copy'); // Copy the selected content
		document.body.removeChild(el); // Remove the textarea element
		alert('Copied to clipboard: ' + text); // Alert the user
	}
	</script>
	
	<script>
	// Make rows draggable and handle sorting
	document.addEventListener('DOMContentLoaded', () => {
		const table = document.querySelector('table.w3-table'); // Select the table

		let draggedRow = null;

		table.addEventListener('dragstart', (event) => {
			draggedRow = event.target; // Store the dragged row
			event.target.style.opacity = 0.5; // Reduce opacity for visual feedback
		});

		table.addEventListener('dragover', (event) => {
			event.preventDefault(); // Allow dropping
		});

		table.addEventListener('drop', (event) => {
			event.preventDefault();
			if (draggedRow && event.target.tagName === 'TD') {
				const targetRow = event.target.closest('tr'); // Get the closest row to the drop target
				if (targetRow && draggedRow !== targetRow) {
					const isAbove = draggedRow.compareDocumentPosition(targetRow) & Node.DOCUMENT_POSITION_PRECEDING;

					// Insert the dragged row above or below the target row
					if (isAbove) {
						targetRow.parentNode.insertBefore(draggedRow, targetRow);
					} else {
						targetRow.parentNode.insertBefore(draggedRow, targetRow.nextSibling);
					}

					  // Update the Alpine.js `datas` array to reflect the new order
					  updateDataOrder();
				}
			}
			draggedRow.style.opacity = ''; // Reset opacity
			draggedRow = null; // Clear draggedRow
		});

		table.addEventListener('dragend', (event) => {
			event.target.style.opacity = ''; // Reset opacity
		});

		// Update the Alpine.js data array to match the new order
		function updateDataOrder() {
			const rows = table.querySelectorAll('tr:not(.w3-gray)'); // Exclude header row
			const newOrder = Array.from(rows).map((row) => {
				const title = row.querySelector('b').textContent;
				const option = row.querySelector('i').textContent;
				const id = row.querySelector('code span').textContent;
				const dataInput = row.querySelector('input, textarea'); // Get input or textarea
				const data = dataInput ? dataInput.value : ''; // Retrieve the value
				return { title, option, id, data }; // Ensure all fields are updated
			});
			// Update Alpine.js state
			document.querySelector('[x-data]').__x.$data.datas = newOrder;

			// Update the hidden textarea for form submission
			const textarea = document.querySelector('textarea[name="json"]');
			if (textarea) {
				textarea.value = JSON.stringify(newOrder, null, 4);
			}
		}
	});
	</script>

</div>

<?php if (isset($_POST['saver'])) {
    $jsonData = $_POST['json'];
    $decodedData = json_decode($jsonData, true);
    $decodedData = array_map(function ($value) {
        return str_replace("\'", "\u0027", $value);
    }, $decodedData);
    file_put_contents($file, json_encode($decodedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo ("<meta http-equiv='refresh' content='0'>");
};
?>
