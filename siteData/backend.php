<?php
	$file = GSDATAOTHERPATH . 'SiteDataSettings.json';
	if (!file_exists($file)) {
		file_put_contents($file, '[]');
	};
	$datas = file_get_contents(GSDATAOTHERPATH . 'SiteDataSettings.json');
?>
<style>
	.btn{border-radius:5px; border:none; padding:10px; color:#fff; cursor: pointer;}
	.btn.data{background:#1E90FF;}
	.btn.settings{background:black;}
	.btn.new{background:orange;}
	.btn.delete{background:red;}
	.btn.save{background:green!important;}
</style>

<h3>Site Data 🤖</h3>
<p>Add custom easy to update fields in your site templates, like Phone Number, Email Address, Business Hours, etc.</p>

<script src="//unpkg.com/alpinejs" defer></script>

<div class="test" x-data='{
	datas: <?php echo $datas; ?>,
	active:"data",
	options:["input","textarea"],

	titleElement:"",
	optionElement:"input",

	pushNew(){
		if(this.titleElement == ""){
			alert("Add title label!");
		}else{
			let newObject ={};
			newObject.id = this.titleElement.toLowerCase().replace(/\s+/g, "").replace(/[^\w\s]/gi, "");
			newObject.title = this.titleElement;
			newObject.option = this.optionElement;
			newObject.data = JSON.stringify(newObject.data);
			this.datas.push(newObject);
			this.titleElement = "";
			this.optionElement = "input";
		}
	}
}'>

	<button @click="active = 'data'" class="btn data">Data 📰</button>
	<button @click="active = 'settings'" class="btn settings">Settings ⚙️</button>
	
	<!-- Start Data -->
	<div class="Data" x-show="active == 'data'" style="width:100%;background:#fafafa; border:solid 1px #ddd; padding:10px; margin:5px 0;margin-top:20px;display:block;">
		<h3> Data</h3>
		<hr>

		<template x-for="data in datas" :key="data.id+data.option">
			<div>
				<b x-text="data.title"></b><br>
				<input type="text" x-value="data.data" x-show="data.option=='input'" x-model="data.data" style="width:100%;padding:7px;margin-top:10px;background:rgba(0,0,0,0.1);border:none;">
				<textarea x-show="data.option=='textarea'" x-model="data.data" x-html="data.data.replace('\u0027',`'`)" style="width:100%;padding:7px;margin-top:10px;background:rgba(0,0,0,0.1);border:none;" rows="5"></textarea>
			</div>
		</template>
	</div>
	
	<!-- Start Settings -->
	<div class="Settings" x-show="active == 'settings'"
		style="width:100%;background:#fafafa;border:solid 1px #ddd;padding:10px;margin:5px 0;margin-top:20px;display:block;">
		<h3> Setttings</h3>
		<hr>
		<br>
		<input type="text" pattern="[A-Za-z0-9]+" placeholder="Title" x-model="titleElement"
			style="padding:0.4rem;border-radius:5px;border:none;border:solid 1px #ddd;width:80%">
		<select x-model="optionElement" style="padding:0.4rem;border-radius:5px;border:none;border:solid 1px #ddd;">
			<option value="choose" disabled>Choose</option>
			<template x-for="option in options" :key="option">
				<option x-value="option" x-text="option"></option>
			</template>
		</select>
		<button @click="pushNew" class="btn new">Add New</button>
		<hr style="margin:20px 0;">
		<template x-for="(data,index) in datas" :key="data.title+data.option">
			<div style="width:100%;padding:0.5rem;border:solid 1px #ddd;display:grid;grid-template-columns:1fr 1fr 300px 90px;gap:5px;margin:5px 0">
				<b x-text="data.title"></b>
				<i x-text="'Input Type: '+data.option"></i>
				<code style="border:solid 1px #ddd;text-align:center;color:blue;">
				[% siteData=<span x-text="data.id"></span> %]	 
				<br>
				&lt;?php siteData("<span x-text="data.id"></span>");?&gt;
				</code>
				<button class="btn delete" @click="datas.splice(index, 1);">Delete🗑️</button>
			</div>
		</template>
	</div>

	<form method="post">
		<textarea name="json" x-html="JSON.stringify(datas, null, 4)" style="display:none"></textarea>
		<input type="submit" name="saver" value="Save Inputs 💾" class="btn save">
	</form>
</div>

<?php if (isset($_POST['saver'])) {
	$jsonData = $_POST['json'];
	$decodedData = json_decode($jsonData, true);
	$decodedData = array_map(function ($value) {
		return str_replace("'", "\u0027", $value);
	}, $decodedData);
	file_put_contents($file, json_encode($decodedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	echo ("<meta http-equiv='refresh' content='0'>");
};
?>
