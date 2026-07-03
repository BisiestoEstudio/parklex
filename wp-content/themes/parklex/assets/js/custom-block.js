wp.domReady(() => {

    const buttonStyles = {
        small: 'Glass',
        large: 'Link',
    }

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