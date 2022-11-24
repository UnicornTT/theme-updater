function adminInitializeBlock( initializeCallback, parentSelector ) {
    return () => {
        const $parentContainer = $(parentSelector)

        initializeCallback()

        const buttons = $parentContainer.find('button');
        const inputsButton = $parentContainer.find('input[type="button"]')
        const links = $parentContainer.find('a')

        buttons.off('click').off('blur').off('mouseout').off('mousemove').off('mouseleave').off('mouseenter').off('mouseover')
        inputsButton.off('click').off('blur').off('mouseout').off('mousemove').off('mouseleave').off('mouseenter').off('mouseover')
        links.off('click').off('blur').off('mouseout').off('mousemove').off('mouseleave').off('mouseenter').off('mouseover')
    }
}