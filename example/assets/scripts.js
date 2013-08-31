$(function() {
	$(".toc").tocify({
		context: ".snippet-content",
		extendPage: false,
		showAndHide: false,
		hashGenerator: "pretty",
		highlightOffset: 0
	});
});