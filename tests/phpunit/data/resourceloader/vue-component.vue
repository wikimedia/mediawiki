<template>
	<!-- Outer comment -->
	<div class="mw-vue-test">
		<!--
			Inner comment
			with multiple lines
			and tabs
		-->
		<p>Hello\n</p>
		<p>{{ hello }}</p>
		<pre>
			foo\
			bar
		</pre>
	</div>
</template>

<script>
module.exports = {
	data: function () {
		return {
			hello: 'world'
		};
	}
};
</script>

<style lang="less">
.mw-vue-test {
	&:hover {
		background-color: red;
	}
}
</style>
