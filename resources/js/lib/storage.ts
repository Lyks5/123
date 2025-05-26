export const FormStorage = {
  saveFormState: (formId: string, data: any) => {
    try {
      localStorage.setItem(`form_${formId}`, JSON.stringify(data));
    } catch (error) {
      console.error('Error saving form state:', error);
    }
  },

  loadFormState: (formId: string) => {
    try {
      const data = localStorage.getItem(`form_${formId}`);
      return data ? JSON.parse(data) : null;
    } catch (error) {
      console.error('Error loading form state:', error);
      return null;
    }
  },

  clearFormState: (formId: string) => {
    try {
      localStorage.removeItem(`form_${formId}`);
    } catch (error) {
      console.error('Error clearing form state:', error);
    }
  }
};