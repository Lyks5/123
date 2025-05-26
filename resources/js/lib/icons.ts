import {
  ImageIcon,
  DollarSign,
  ListTree,
  Save,
  CheckCircle,
  AlertCircle,
  Loader2,
  Upload,
  X,
  Plus,
  Trash,
  GripVertical
} from 'lucide-react';

export const Icons = {
  image: ImageIcon,
  price: DollarSign,
  attributes: ListTree,
  save: Save,
  success: CheckCircle,
  error: AlertCircle,
  spinner: Loader2,
  upload: Upload,
  close: X,
  add: Plus,
  delete: Trash,
  drag: GripVertical
};

export type IconType = keyof typeof Icons;