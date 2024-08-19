import { useEventBus } from "@/EventBus";
import { useState, useEffect } from "react";
import { v4 as uuidv4 } from "uuid";
import UserAvatar from "./UserAvatar";
import { Link } from "@inertiajs/react";

export default function NewMessageNotification({}) {
    const [toasts, setToasts] = useState([]);
    const { on } = useEventBus();

    useEffect(() => {
        on("newMessageNotification", ({message, user, group_id}) => {
            const uuid = uuidv4();

            setToasts((oldToasts) => [...oldToasts, { uuid, message, user, group_id }]);

            setTimeout(() => {
                setToasts((oldToasts) =>
                    oldToasts.filter((toast) => toast.uuid !== uuid)
                );
            }, 5000);
        });
    }, [on]);

    return (
        <div className="toast toast-top toast-center min-w-[300px]">
            {toasts.map((toast, index) => (
                <div
                    key={toast.uuid}
                    className="alert alert-success py-3 px-4 text-gray-100 rounded-md"
                >
                    <Link href={
                        toast.group_id
                            ? route('chat.group', toast.group_id)
                            : route('chat.user', toast.user.id)
                    }
                    className="flex items-center gap-2"
                    >
                        <UserAvatar user={toast.user} />
                        <span>{toast.message}</span>
                    </Link>
                </div>
            ))}
        </div>
    );
}
