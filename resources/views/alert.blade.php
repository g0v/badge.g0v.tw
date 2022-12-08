<script>
alert({{ Js::from($message) }});
document.location = {{ Js::from($next) }};
</script>
