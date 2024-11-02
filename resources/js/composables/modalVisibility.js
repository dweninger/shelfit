import {ref} from 'vue';

export default function modalVisibility() {
    const isModalVisible = ref(false);

    const showModal = () => {
        isModalVisible.value = true;
    };

    const hideModal = () => {
        isModalVisible.value = false;
    };

    return {
        isModalVisible,
        showModal,
        hideModal
    }
}
