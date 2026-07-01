wp.domReady(() => {

    const buttonStyles = {
        small: 'Small',
        large: 'Large',
    }

    wp.blocks.unregisterBlockStyle('core/button', ['fill', 'outline']);
    bisRegisterBlockVariation('core/button', buttonStyles);

});




function bisRegisterBlockVariation(blockName, data, isDefault = false) {
    Object.keys(data).forEach(key => {
        let blockData = {
            name: key,
            label: data[key]
        }

        if (isDefault) {
            blockData.default = true;
            isDefault = false;
        }
        wp.blocks.registerBlockStyle(blockName, blockData);
    });
}