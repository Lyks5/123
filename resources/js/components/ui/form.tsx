import React from 'react';
import { UseFormReturn } from 'react-hook-form';

interface FormProps extends React.FormHTMLAttributes<HTMLFormElement> {
    children: React.ReactNode;
}

interface FormFieldProps {
    name: string;
    control: any;
    render: (props: { field: any }) => React.ReactNode;
}

interface FormItemProps extends React.HTMLAttributes<HTMLDivElement> {
    children: React.ReactNode;
}

interface FormLabelProps {
    children: React.ReactNode;
}

interface FormControlProps {
    children: React.ReactNode;
}

interface FormMessageProps {
    children?: React.ReactNode;
}

export const FormMessage: React.FC<FormMessageProps> = ({ children }) => {
    return children ? (
        <p className="text-sm text-red-500 mt-1">
            {children}
        </p>
    ) : null;
};

export const Form: React.FC<FormProps> = ({ children, ...props }) => {
    return (
        <form {...props}>
            {children}
        </form>
    );
};

export const FormField: React.FC<FormFieldProps> = ({ name, control, render }) => {
    return render({ field: { name, ...control.register(name) } });
};

export const FormItem: React.FC<FormItemProps> = ({ children, className, ...props }) => {
    return (
        <div className={`space-y-2 ${className || ''}`} {...props}>
            {children}
        </div>
    );
};

export const FormLabel: React.FC<FormLabelProps> = ({ children }) => {
    return (
        <label className="text-sm font-medium leading-none">
            {children}
        </label>
    );
};

export const FormControl: React.FC<FormControlProps> = ({ children }) => {
    return (
        <div className="mt-2">
            {children}
        </div>
    );
};

Form.displayName = 'Form';
FormField.displayName = 'FormField';
FormItem.displayName = 'FormItem';
FormLabel.displayName = 'FormLabel';
FormControl.displayName = 'FormControl';
FormMessage.displayName = 'FormMessage';